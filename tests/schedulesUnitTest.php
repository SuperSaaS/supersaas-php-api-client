<?php

class SchedulesUnitTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $config = new SuperSaas\Configuration;
        $config->account_name = 'accnt';
        $config->password = 'pwd';
        $config->dry_run = true;
        $this->client = new SuperSaas\Client($config);
    }

    public function testGetList()
    {
        $this->assertNotNull($this->client->schedules->getList());
    }

    public function testResources() {
        $this->assertNotNull($this->client->schedules->resources(12345));
    }
}