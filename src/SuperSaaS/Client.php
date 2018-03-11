<?php namespace SuperSaaS;

use SuperSaaS\API;

class Client
{
    const API_VERSION = "1";
    const VERSION = "1.0.0";

    /**
     * @var string
     */
    public $account_name;

    /**
     * @var string
     */
    public $password;

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

    public static function configure($account_name, $password, $dry_run=FALSE, $verbose=FALSE, $host=NULL) {
        self::Instance()->account_name = $account_name;
        self::Instance()->password = $password;
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
        $this->password = $configuration->password;
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
     * @throws \Exception
     */
    public function request ($http_method, $path, $params = array(), $query = array()) {
        if (empty($this->account_name))
        {
            throw new \Exception(new SSS_Exception("Account name not configured. Call `SuperSaaS_Client.configure`."));
        }
        if (empty($this->password))
        {
            throw new \Exception(new SSS_Exception("Account password not configured. Call `SuperSaaS_Client.configure`."));
        }
        $params = $this->removeEmptyKeys($params);
        $query = $this->removeEmptyKeys($query);

        $params['account_name'] = $this->account_name;

        if (!in_array($http_method, array("GET", "POST", "PUT", "DELETE"))) {
            throw new \Exception(new SSS_Exception("Invalid HTTP Method: " . $http_method . ". Only `GET`, `POST`, `PUT`, `DELETE` supported."));
        }

        $url = $this->host . "/api" . $path . ".json";
        if (!empty($query)) {
            $url = $url . '?' . http_build_query($query);
        }

        $http = array(
            'method' => $http_method,
            'header'  => array(
                'Authorization: Basic ' . base64_encode($this->account_name . ':' . $this->password),
                'Accept: application/json',
                'Content-Type: application/json',
                'User-Agent: ' . $this->userAgent(),
            )
        );
        if ($http_method !== 'GET' && !empty($params)) {
            $http['content'] = json_encode($params);
        }

        if ($this->verbose) {
            echo("### SuperSaaS Client Request:");
            echo($http_method . " " . $url);
            echo($params);
            echo("------------------------------");
        }

        $this->lastRequest = $http;
        if ($this->dry_run) {
            return array();
        }

        $req  = stream_context_create(array('http' => $http));
        $res = file_get_contents($url, false, $req);
        if ($res == FALSE) {
            throw new \Exception(new SSS_Exception("HTTP Request Error " . $url));
        } else if (!empty($res)) {
            if ($this->verbose) {
                echo("Response:");
                echo($res);
                echo("==============================");
            }

            $obj = json_decode($res);
            return $obj;
        } else {
            return array();
        }
    }

    private function removeEmptyKeys ($arr) {
        $valueArr = array();
        foreach ($arr as $key=>$val) {
            if ($val !== NULL && $val !== "")
                $valueArr[$key] = $val;
        }
        return $valueArr;
    }

    private function userAgent () {
        return "SSS/" . self::VERSION . " PHP/" . phpversion() . " API/" . self::API_VERSION;
    }
}