#!/usr/bin/php
<?php
    include("common.php");
    $client = SuperSaaS\Client::Instance();

    $schedule_id = getenv('SSS_API_SCHEDULE');
    if (empty($schedule_id)) {
      echo "ERROR! Missing schedule id. Rerun the script with your schedule id, e.g.\n\r";
      echo "    export SSS_API_SCHEDULE=<scheduleid> && php -f ./examples/appointments.php\n\r";
      return;
    } else {
      $schedule_id = intval($schedule_id);
    }

    echo "\n\r# SuperSaaS Appointments Example\n\r";

    echo "## Account:  ".$client->account_name ."\n\r";

//    echo "\n\rlisting available...\n\r";
//    $from = date('Y-m-d H:i:s');
//    $client->appointments->available($schedule_id, $from);
//
//    $user_id = getenv('SSS_API_UID');
//    if (!empty($user_id)) {
//      echo "\n\rlisting user agenda...\n\r";
//      $client->appointments->agenda($schedule_id, $user_id, $from);
//    }

    $appointment_id = getenv('SSS_APPOINTMENT_ID');
    if (!empty($appointment_id)) {
        echo "\n\rdeleting appointment" . $appointment_id . ",...\n\r";
        $client->appointments->delete($schedule_id, $appointment_id);
    }

    echo "\n\r";
?>