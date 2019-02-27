<?php
    set_include_path(get_include_path().":".dirname(__FILE__)."/../src/SuperSaaS");

    spl_autoload_register(function ($class_name) {
        $file_name = str_replace("\\", "/", $class_name);
        $file_name = dirname(__FILE__)."/../src/".$file_name.".php";
        require $file_name;
    });

    include("Client.php");

    $client = SuperSaaS\Client::Instance();
    $client->verbose = TRUE;

    if (empty($client->account_name) || empty($client->api_key)) {
      echo "ERROR! Missing account credentials. Rerun the script with your credentials, e.g.\n\r";
      echo "    export SSS_API_ACCOUNT_NAME=<myaccountname> && export SSS_API_KEY=<myapikey> && php -f ./examples/appointments.php\n\r";
      return;
    }