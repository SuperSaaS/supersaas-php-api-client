<?php
namespace SuperSaaS\Tests;

use PHPUnit\Framework\TestCase;
use SuperSaaS\Configuration;
use SuperSaaS\Client;
class SchedulesUnitTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $config = new Configuration;
        $config->account_name = 'accnt';
        $config->api_key = 'xxxxxxxxxxxxxxxxxxxxxx';
        $config->dry_run = true;
        $this->client = new Client($config);
    }

    public function testGetList()
    {
        $this->assertNotNull($this->client->schedules->getList());
    }

    public function testResources() {
        $this->assertNotNull($this->client->schedules->resources(12345));
    }

    public function testFieldList() {
        $this->assertNotNull($this->client->schedules->fieldList(12345));
    }
}