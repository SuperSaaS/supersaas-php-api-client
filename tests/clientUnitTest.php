<?php
namespace SuperSaaS\Tests;

use PHPUnit\Framework\TestCase;
use SuperSaaS\Configuration;
use SuperSaaS\Client;
use SuperSaaS\RateLimiter;
class ClientUnitTest extends TestCase
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
        if (getenv('SSS_PHP_RATE_LIMITER_TEST') !== 'true') {
            $this->markTestSkipped('Rate limiter test is skipped. Set SSS_PHP_RATE_LIMITER_TEST=true to enable it.');
        }

        // Max burst allowed without errors
        for ($i = 0; $i < 4; $i++) {
            $startTime = microtime(true);
            $this->client->request('GET', '/test'); // Assuming there's a public throttle method or making it accessible
            $endTime = microtime(true);
            $elapsedTime = $endTime - $startTime;
            
            $this->assertLessThan(1+0.1, $elapsedTime, 
                "Expected no throttling, but got a delay of {$elapsedTime} seconds");
        }

        // Wait for window to reset
        usleep((1 + 0.1) * 1000000); // Convert to microseconds

        // Another burst of MAX_REQUESTS should now be allowed
        for ($i = 0; $i < 4; $i++) {
            $startTime = microtime(true);
            $this->client->request('GET', '/test');
            $endTime = microtime(true);
            $elapsedTime = $endTime - $startTime;
            
            $this->assertLessThan(1+0.1, $elapsedTime, 
                "Expected no throttling, but got a delay of {$elapsedTime} seconds");
        }

        // Wait for window to expire and reset
        usleep((1 + 0.1) * 1000000);

        // Test longer throttling to prevent potential DDOS
        $startTime = microtime(true);
        for ($i = 0; $i < 20; $i++) {
            $this->client->request('GET', '/test');
        }
        $endTime = microtime(true);
        $elapsedTime = $endTime - $startTime;

        $this->assertGreaterThan(5.0, $elapsedTime, 
            "Expected throttling, {$elapsedTime} seconds");
        $this->assertLessThan(5.1, $elapsedTime, 
            "Expected throttling, {$elapsedTime} seconds");
    }

    public function testInstanceConfiguration() {
        Client::configure('accnt', 'xxxxxxxxxxxxxxxxxxxxxx', true, true, 'host');
        $this->assertEquals('accnt', Client::Instance()->account_name);
        $this->assertEquals('xxxxxxxxxxxxxxxxxxxxxx', Client::Instance()->api_key);
        $this->assertEquals(true, Client::Instance()->dry_run);
        $this->assertEquals(true, Client::Instance()->verbose);
        $this->assertEquals('host', Client::Instance()->host);
    }
}