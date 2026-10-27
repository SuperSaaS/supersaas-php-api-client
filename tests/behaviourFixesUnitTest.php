<?php
namespace SuperSaaS\Tests;

use PHPUnit\Framework\TestCase;
use SuperSaaS\Client;
use SuperSaaS\Configuration;
use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

/**
 * Records the arguments of every request, so tests can tell body params from URL query params.
 */
class RecordingClient extends Client
{
    public array $requests = array();

    public function request($http_method, $path, $params = array(), $query = array())
    {
        $this->requests[] = array('method' => $http_method, 'path' => $path, 'params' => $params, 'query' => $query);
        return parent::request($http_method, $path, $params, $query);
    }
}

/**
 * Regression tests for the behaviour fixes (role, credit, delete query, missing keys, verbose redaction,
 * response headers without PHP 8.4).
 */
class BehaviourFixesUnitTest extends TestCase
{
    protected RecordingClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $config = new Configuration;
        $config->account_name = 'accnt';
        $config->api_key = 'xxxxxxxxxxxxxxxxxxxxxx';
        $config->dry_run = true;
        $this->client = new RecordingClient($config);
    }

    public function testRequestWithoutResponseThrowsHttpError()
    {
        $this->client->dry_run = false;
        $this->client->host = 'http://127.0.0.1:1';
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('HTTP Request Error: 0 http://127.0.0.1:1/api/schedules.json');
        $this->client->get('/schedules');
    }
}
