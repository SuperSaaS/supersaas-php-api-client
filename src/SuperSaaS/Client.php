<?php namespace SuperSaaS;

use AllowDynamicProperties;
use SuperSaaS\API\Appointments;
use SuperSaaS\API\Forms;
use SuperSaaS\API\Groups;
use SuperSaaS\API\Promotions;
use SuperSaaS\API\Schedules;
use SuperSaaS\API\Users;

//use SuperSaaS\API;
//use SuperSaaS\SSS_Exception;
//use SuperSaaS\RateLimiter;

/**
 * @property array $lastRequest
 */
#[AllowDynamicProperties] class Client
{
    const API_VERSION = "3";
    const VERSION = "2.0.0";

    /**
     * @var string
     */
    public string|array|false $account_name;

    /**
     * @var string
     */
    public string|array|false $api_key;

    /**
     * @var string
     */
    public string|array|false $host;

    /**
     * @var bool
     */
    public bool $verbose;

    /**
     * @var bool
     */
    public bool $dry_run;

    /**
     * @var Appointments
     */
    public Appointments $appointments;

    /**
     * @var Forms
     */
    public Forms $forms;

    /**
     * @var Schedules
     */
    public Schedules $schedules;

    /**
     * @var Users
     */
    public Users $users;

    /**
     * @var Groups
     */
    public Groups $groups;

    /**
     * @var Promotions
     */
    public Promotions $promotions;


    public static function instance(): ?Client
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self(new Configuration());
        }
        return $instance;
    }

    public static function configure($account_name, $api_key, $dry_run=false, $verbose=false, $host=Configuration::DEFAULT_HOST): void
    {
        self::instance()->account_name = $account_name;
        self::instance()->api_key = $api_key;
        self::instance()->dry_run = $dry_run;
        self::instance()->verbose = $verbose;
        self::instance()->host = $host;
    }

    public function __construct($configuration=null)
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
        $this->groups = new API\Groups($this);
        $this->promotions = new API\Promotions($this);
    }

    /**
     * @throws SSS_Exception
     */
    public function get($path, $query = array())
    {
        return $this->request("GET", $path, array(), $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function post($path, $params = array(), $query = array())
    {
        return $this->request("POST", $path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function put($path, $params = array(), $query = array())
    {
        return $this->request("PUT", $path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function delete($path, $params = array(), $query = array())
    {
        return $this->request("DELETE", $path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function request($http_method, $path, $params = array(), $query = array())
    {

        RateLimiter::throttle();
        if (empty($this->account_name)) {
            throw new SSS_Exception("Account name not configured. Call `SuperSaaS_Client.configure`.");
        }
        if (empty($this->api_key)) {
            throw new SSS_Exception("Account api key not configured. Call `SuperSaaS_Client.configure`.");
        }
        $params = $this->_removeEmptyKeys($params);
        $query = $this->_removeEmptyKeys($query);

        $params['account_name'] = $this->account_name;

        if (!in_array($http_method, array("GET", "POST", "PUT", "DELETE"))) {
            throw new SSS_Exception("Invalid HTTP Method: " . $http_method . ". Only `GET`, `POST`, `PUT`, `DELETE` supported.");
        }

        if ($this->verbose) {
            echo PHP_EOL . var_dump($query) . PHP_EOL;
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
                'User-Agent: ' . $this->_userAgent(),
            ),
            'ignore_errors' => true,
        );
        if ($http_method !== 'GET' && !empty($params)) {
            $http['content'] = json_encode($params);
        }


        if ($this->verbose) {
            echo("### SuperSaaS Client Request:" . PHP_EOL);
            echo($http_method . " " . $url . PHP_EOL);
            echo("DATA:" . PHP_EOL);
            $this->_printArray($params);
            echo("------------------------------" . PHP_EOL);
        }

        $this->lastRequest = $http;
        if ($this->dry_run) {
            return array();
        }

        $req = stream_context_create(array('http' => $http));
        $res = @file_get_contents($url, false, $req);
        $statusCode = $this->_httpStatusCode($http_response_header);

        if ($statusCode < 200 || $statusCode > 299) {
            if ($this->verbose) {
                echo("Error Response (" . $statusCode . "):" . PHP_EOL);
                echo($res . PHP_EOL);
                echo("==============================" . PHP_EOL);
            }
            throw new SSS_Exception("HTTP Request Error: {$statusCode} " . $url);
        } else if (!empty($res)) {
            if ($this->verbose) {
                echo("Response:" . PHP_EOL);
                echo($res . PHP_EOL);
                echo("==============================" . PHP_EOL);
            }

            $obj = json_decode($res, true);
            return $obj;
        } else {
            return $this->_findLocation($http_response_header);
        }
    }

    private function _findLocation($array): array|string
    {
        foreach ($array as $line) {
            if (str_contains($line, 'location:')) {
                $parts = explode(':', $line, 2);  // Split at the first colon
                if (count($parts) === 2) {
                    return trim($parts[1]); // Return the location URL, trimmed for spaces
                }
            }
        }

        return array(); //no location
    }

    private function _removeEmptyKeys($arr): array
    {
        $valueArr = array();
        foreach ($arr as $key=>$val) {
            if ($val !== null && $val !== "") {
                if ($this->_isAssociativeArray($val)) {
                    $val = $this->_removeEmptyKeys($val);
                }
                $valueArr[$key] = $val;
            }
        }
        return $valueArr;
    }

    private function _userAgent()
    {
        return "SSS/" . self::VERSION . " PHP/" . phpversion() . " API/" . self::API_VERSION;
    }

    private function _isAssociativeArray($arr)
    {
        if (is_array($arr)) {
            return count(array_filter(array_keys($arr), 'is_string')) > 0;
        } else {
            return false;
        }
    }

    private function _httpStatusCode($headers): int
    {
        if (is_array($headers)) {
            $parts = explode(' ', $headers[0]);
            print_r($parts);
            if (count($parts) > 1) {
                return intval($parts[1]);
            }
        }
        return 0;
    }

    private function _printArray($arr): void
    {
        foreach ($arr as $key => $val) {
            if ($this->_isAssociativeArray($val)) {
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
