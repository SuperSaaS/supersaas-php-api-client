#!/usr/bin/php
<?php
include("common.php");
$client = SuperSaaS\Client::Instance();
$client->verbose = true;

echo "\n\r# SuperSaaS Users Example\n\r";

echo "## Account:  ".$client->account_name ."\n\r";

echo "creating new user...";

$attributes = array(
    'name' => 'testing123@example.com',
    'email' => 'testing123@example.com',
    'password' => 'pass123',
    'full_name' => 'Tester Test',
    'address' => '123 St, City',
    'mobile' => '555-5555',
    'phone' => '555-5555',
    'country' => 'US',
    'field_1' => 'f 1',
    'field_2' => 'f 2',
    'super_field' => 'sf',
    'role' => 3
);

$client->users->create($attributes, '123456fk');

echo "\n\r";
?>