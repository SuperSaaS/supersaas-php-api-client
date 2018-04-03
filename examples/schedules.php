#!/usr/bin/php
<?php
    include("common.php");
    $client = SuperSaaS\Client::Instance();

    echo "\n\r# SuperSaaS Schedules Example\n\r";

    echo "## Account:  ".$client->account_name ."\n\r";

    echo "\n\rlisting schedules...";
    echo "\n\r#### client->schedules->getList()\n\r";
    $schedules = $client->schedules->getList();

    echo "\n\rlisting schedule resources...";
    foreach($schedules as $schedule) {
      try {
        $client->schedules->resources($schedule->id);
      } catch (Exception $e) {
      }
    }
    echo "\n\r";
?>