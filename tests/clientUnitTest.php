<?php

class ClientUnitTest extends PHPUnit_Framework_TestCase
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

    public function testApi()
    {
        $this->assertInstanceOf('SuperSaaS\API\Appointments', $this->client->appointments);
        $this->assertInstanceOf('SuperSaaS\API\Forms', $this->client->forms);
        $this->assertInstanceOf('SuperSaaS\API\Schedules', $this->client->schedules);
        $this->assertInstanceOf('SuperSaaS\API\Users', $this->client->users);
    }

    public function testRequest() {
        $query = array('test' => 1);
        $params = array();
        $this->assertNotNull($this->client->request('GET', $params, $query));
    }

    public function testInstanceConfiguration() {
        SuperSaas\Client::configure('accnt', 'pwd', true, true, 'host');
        $this->assertEquals('accnt', SuperSaas\Client::Instance()->account_name);
        $this->assertEquals('pwd', SuperSaas\Client::Instance()->password);
        $this->assertEquals(true, SuperSaas\Client::Instance()->dry_run);
        $this->assertEquals(true, SuperSaas\Client::Instance()->verbose);
        $this->assertEquals('host', SuperSaas\Client::Instance()->host);
    }
}