<?php namespace SuperSaaS;

use SuperSaaS\API;
use SuperSaaS\SSS_Exception;


class Client
{
    const API_VERSION = "2";
    const VERSION = "1.1.2";

    /**
     * @var string
     */
    public $account_name;

    /**
     * @var string
     */
    public $api_key;

    /**
     * @var string
     */
    public $host;

    /**
     * @var bool
     */
    public $verbose;

    /**
     * @var bool
     */
    public $dry_run;

    /**
     * @var \SuperSaaS\API\Appointments
     */
    public $appointments;

    /**
     * @var \SuperSaaS\API\Forms
     */
    public $forms;

    /**
     * @var \SuperSaaS\API\Schedules
     */
    public $schedules;

    /**
     * @var \SuperSaaS\API\Users
     */
    public $users;

    /**
     * @var array
     */
    public $lastRequest = array();

    public static function Instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self(new Configuration());
        }
        return $instance;
    }

    public static function configure($account_name, $api_key, $dry_run=FALSE, $verbose=FALSE, $host=Configuration::DEFAULT_HOST) {
        self::Instance()->account_name = $account_name;
        self::Instance()->api_key = $api_key;
        self::Instance()->dry_run = $dry_run;
        self::Instance()->verbose = $verbose;
        self::Instance()->host = $host;
    }

    public function __construct ($configuration=NULL)
    {
        if (!$configuration) {
            $configuration = new Configuration();
        }
        $this->account_name = $configuration->account_name;
        $this->api_key = $configuration->api_key;
        $this->verbose = $configuration->verbose;
        $this->host = $configuration->host;
        $this->dry_run = $configuration->dry_run;

        $this->appointments = new API\Appointments($this);
        $this->forms = new API\Forms($this);
        $this->schedules = new API\Schedules($this);
        $this->users = new API\Users($this);
    }

    public function get ($path, $query = array()) {
        return $this->request("GET", $path, array(), $query);
    }

    public function post ($path, $params = array(), $query = array()) {
        return $this->request("POST", $path, $params, $query);
    }

    public function put ($path, $params = array(), $query = array()) {
        return $this->request("PUT", $path, $params, $query);
    }

    public function delete ($path, $params = array(), $query = array()) {
        return $this->request("DELETE", $path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function request ($http_method, $path, $params = array(), $query = array()) {
        $this->throttle();
        if (empty($this->account_name))
        {
            throw new SSS_Exception("Account name not configured. Call `SuperSaaS_Client.configure`.");
        }
        if (empty($this->api_key))
        {
            throw new SSS_Exception("Account api key not configured. Call `SuperSaaS_Client.configure`.");
        }
        $params = $this->removeEmptyKeys($params);
        $query = $this->removeEmptyKeys($query);

        $params['account_name'] = $this->account_name;

        if (!in_array($http_method, array("GET", "POST", "PUT", "DELETE"))) {
            throw new SSS_Exception("Invalid HTTP Method: " . $http_method . ". Only `GET`, `POST`, `PUT`, `DELETE` supported.");
        }

        if ($this->verbose) {
            echo "\n\n".var_dump($query)."\n\n";
        }

        $url = $this->host . "/api" . $path . ".json";
        if (!empty($query)) {
            $url = $url . '?' . http_build_query($query);
        }

        $http = array(
            'method' => $http_method,
            'header'  => array(
                'Authorization: Basic ' . base64_encode($this->account_name . ':' . $this->api_key),
                'Accept: application/json',
                'Content-Type: application/json',
                'User-Agent: ' . $this->userAgent(),
            ),
            'ignore_errors' => true,
        );
        if ($http_method !== 'GET' && !empty($params)) {
            $http['content'] = json_encode($params);
        }

        if ($this->verbose) {
            echo("### SuperSaaS Client Request:\n\r");
            echo($http_method . " " . $url."\n\r");
            echo("DATA:\n\r");
            $this->printArray($params);
            echo("------------------------------\n\r");
        }

        $this->lastRequest = $http;
        if ($this->dry_run) {
            return array();
        }

        $req = stream_context_create(array('http' => $http));
        $res = @file_get_contents($url, false, $req);
        $statusCode = $this->httpStatusCode($http_response_header);

        if ($statusCode < 200 || $statusCode > 299) {
            if ($this->verbose) {
                echo("Error Response (" . $statusCode . "):\n\r");
                echo($res."\n\r");
                echo("==============================\n\r");
            }
            throw new SSS_Exception("HTTP Request Error " . $url);
        } else if (!empty($res)) {
            if ($this->verbose) {
                echo("Response:\n\r");
                echo($res."\n\r");
                echo("==============================\n\r");
            }

            $obj = json_decode($res, TRUE);
            return $obj;
        } else {
            return array();
        }
    }

    private $requestQueue = [null, null, null, null];
    private $windowSize = 1; // Adjust this to set the time window in seconds.
    private $requestLimit = 4; // Adjust this to set the request limit.

    private function throttle()
    {
        // Represents the timestamp of the oldest request within the time window
        $oldestRequest = array_shift($this->requestQueue);

        // Push the current timestamp into the queue
        $this->requestQueue[] = time();

        // This ensures that the client does not make requests faster than the defined rate limit
        if ($oldestRequest !== null && ($timeElapsed = time() - $oldestRequest) < $this->windowSize) {
            sleep($this->windowSize - $timeElapsed);
        }    
    }

    private function removeEmptyKeys ($arr) {
        $valueArr = array();
        foreach ($arr as $key=>$val) {
            if ($val !== NULL && $val !== "") {
                if ($this->isAssociativeArray($val)) {
                    $val = $this->removeEmptyKeys($val);
                }
                $valueArr[$key] = $val;
            }
        }
        return $valueArr;
    }

    private function userAgent () {
        return "SSS/" . self::VERSION . " PHP/" . phpversion() . " API/" . self::API_VERSION;
    }

    private function isAssociativeArray($arr)
    {
        if (is_array($arr)) {
            return count(array_filter(array_keys($arr), 'is_string')) > 0;
        } else {
            return false;
        }
    }

    private function httpStatusCode($headers)
    {
        if(is_array($headers))
        {
            $parts = explode(' ',$headers[0]);
            if(count($parts) > 1)
                return intval($parts[1]);
        }
        return 0;
    }

    private function printArray($arr) {
        foreach ($arr as $key => $val) {
            if ($this->isAssociativeArray($val)) {
                echo "  $key:\n";
                foreach ($val as $key2 => $val2) {
                    echo "    $key2 = $val2\n";
                }
            } else {
                echo "  $key = $val\n";
            }
        }
    }
}
