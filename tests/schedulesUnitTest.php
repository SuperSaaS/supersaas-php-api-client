<?php
use PHPUnit\Framework\TestCase;

class SchedulesUnitTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $config = new SuperSaas\Configuration;
        $config->account_name = 'accnt';
        $config->api_key = 'xxxxxxxxxxxxxxxxxxxxxx';
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