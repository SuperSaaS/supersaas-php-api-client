<?php namespace SuperSaaS;

class Configuration
{
    const DEFAULT_HOST	= "https://www.supersaas.com";

    public $account_name;
    public $host;
    public $password;
    public $user_name;
    public $dry_run;
    public $verbose;

    public function __construct () {
        $this->account_name = getenv('SSS_API_ACCOUNT_NAME');
        $this->host = getenv('SSS_API_HOST') ? getenv('SSS_API_HOST') : Configuration::DEFAULT_HOST;
        $this->password = getenv('SSS_API_PASSWORD');
        $this->user_name = getenv('SSS_API_USER_NAME');
        $this->dry_run = FALSE;
        $this->verbose = FALSE;
    }
}