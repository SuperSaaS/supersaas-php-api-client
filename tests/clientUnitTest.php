<?php
use PHPUnit\Framework\TestCase;

class ClientUnitTest extends TestCase
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
        $this->assertNotNull($this->client->request('GET', '', $params, $query));
    }

    public function testRequestThrottle()
    {
        if (getenv('RUN_RATE_LIMITER_TEST') !== 'true') {
            $this->markTestSkipped('Rate limiter test is skipped. Set RUN_RATE_LIMITER_TEST=true to enable it.');
        }

        $start = microtime(true);

        for ($i = 0; $i < 25; $i++) {
            $this->client->request('GET', '/test');
        }

        $elapsedTime = microtime(true) - $start;
        $this->assertGreaterThanOrEqual(5.0, $elapsedTime, "Elapsed time between requests should be greater than or equal to 5.0 seconds.");
    }

    public function testInstanceConfiguration() {
        SuperSaas\Client::configure('accnt', 'xxxxxxxxxxxxxxxxxxxxxx', true, true, 'host');
        $this->assertEquals('accnt', SuperSaas\Client::Instance()->account_name);
        $this->assertEquals('xxxxxxxxxxxxxxxxxxxxxx', SuperSaas\Client::Instance()->api_key);
        $this->assertEquals(true, SuperSaas\Client::Instance()->dry_run);
        $this->assertEquals(true, SuperSaas\Client::Instance()->verbose);
        $this->assertEquals('host', SuperSaas\Client::Instance()->host);
    }
}