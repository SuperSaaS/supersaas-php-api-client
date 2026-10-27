#!/usr/bin/php
<?php
include("common.php");
$client = SuperSaaS\Client::Instance();

echo "\n\r# SuperSaaS Forms Example\n\r";

echo "## Account:  " . $client->account_name . "\n\r";

echo "\n\rlisting super forms...";
echo "\n\r#### client->forms->forms()\n\r";
$client->forms->forms();

$form_id = getenv('SSS_API_FORM');
if (empty($form_id)) {
    echo "\n\rSkipping form listing. Rerun the script with your super form id to list its forms, e.g.\n\r";
    echo "    export SSS_API_FORM=<superformid> && php -f ./examples/forms.php\n\r";
} else {
    echo "\n\rlisting forms...";
    echo "\n\r#### client->forms->getList(" . $form_id . ")\n\r";
    $client->forms->getList(intval($form_id));
}

echo "\n\r";
