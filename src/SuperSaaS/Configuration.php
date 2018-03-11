<?php namespace SuperSaaS;

class Configuration
{
    const DEFAULT_HOST	= "http://localhost:3000";

    public $account_name;
    public $host;
    public $password;
    public $user_name;
    public $test;

    public function __construct () {
        $this->account_name = getenv('SSS_SDK_ACCOUNT_NAME');
        $this->host = Configuration::DEFAULT_HOST;
        $this->password = getenv('SSS_SDK_PASSWORD');
        $this->user_name = getenv('SSS_SDK_USER_NAME');
        $this->test = FALSE;
    }
}