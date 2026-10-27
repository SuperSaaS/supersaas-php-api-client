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

    private function lastBody(): array
    {
        return json_decode($this->client->lastRequest['content'], true);
    }

    private function lastCall(): array
    {
        return end($this->client->requests);
    }

    /**
     * Runs $fn and turns any PHP warning or notice into a test failure.
     */
    private function withoutWarnings(callable $fn): mixed
    {
        set_error_handler(function ($errno, $errstr) {
            $this->fail("PHP warning: $errstr");
        });
        try {
            return $fn();
        } finally {
            restore_error_handler();
        }
    }

    public function testCreateUserSendsRole()
    {
        $this->client->users->create(array('name' => 'Test', 'role' => 4));
        $this->assertSame(4, $this->lastBody()['user']['role']);
    }

    public function testUpdateUserSendsRole()
    {
        $this->client->users->update(12345, array('role' => -1));
        $this->assertSame(-1, $this->lastBody()['user']['role']);
    }

    public function testInvalidRoleStillThrows()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Invalid option parameter: 5. Must be one of 3, 4, -1');
        $this->client->users->create(array('name' => 'Test', 'role' => 5));
    }

    public function testUserModelReadsCredit()
    {
        $user = new Models\User(array('address' => 'Main St 1', 'credit' => 12));
        $this->assertSame(12, $user->credit);
        $this->assertSame('Main St 1', $user->address);
    }

    public function testDeleteUserSendsWebhookAsQuery()
    {
        $this->client->users->delete(12345, true);
        $call = $this->lastCall();
        $this->assertSame('DELETE', $call['method']);
        $this->assertSame(array(), $call['params']);
        $this->assertSame(array('webhook' => 'true'), $call['query']);
        $this->assertSame(array('account_name' => 'accnt'), $this->lastBody());
    }

    public function testDeleteAppointmentSendsScheduleIdAndWebhookAsQuery()
    {
        $this->client->appointments->delete(12345, 67890, true);
        $call = $this->lastCall();
        $this->assertSame('/bookings/67890', $call['path']);
        $this->assertSame(array(), $call['params']);
        $this->assertSame(array('schedule_id' => 12345, 'webhook' => 'true'), $call['query']);
    }

    public function testCreateUserWithOnlyNameDoesNotWarn()
    {
        $this->withoutWarnings(fn() => $this->client->users->create(array('name' => 'Test')));
        $this->assertSame(array('name' => 'Test'), $this->lastBody()['user']);
    }

    public function testCreateUserWithoutNameStillThrows()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Required parameter is missing.');
        $this->withoutWarnings(fn() => $this->client->users->create(array('email' => 'test@example.com')));
    }

    public function testUpdateUserWithPartialAttributesDoesNotWarn()
    {
        $this->withoutWarnings(fn() => $this->client->users->update(12345, array('email' => 'new@example.com')));
        $this->assertSame(array('email' => 'new@example.com'), $this->lastBody()['user']);
    }

    public function testResourceAndScheduleModelsWithoutAttributesDoNotWarn()
    {
        $resource = $this->withoutWarnings(fn() => new Models\Resource());
        $schedule = $this->withoutWarnings(fn() => new Models\Schedule(array('id' => 1)));
        $this->assertNull($resource->id);
        $this->assertSame(1, $schedule->id);
        $this->assertNull($schedule->name);
    }

    public function testVerboseOutputRedactsPassword()
    {
        $this->client->verbose = true;
        ob_start();
        try {
            $this->client->users->create(array('name' => 'Test', 'password' => 'secret123'));
        } finally {
            $output = ob_get_clean();
        }
        $this->assertStringNotContainsString('secret123', $output);
        $this->assertStringContainsString('password = [FILTERED]', $output);
        $this->assertStringContainsString('name = Test', $output);
        $this->assertStringNotContainsString(base64_encode('accnt:xxxxxxxxxxxxxxxxxxxxxx'), $output);
        // Only the output is redacted, the request still carries the password.
        $this->assertSame('secret123', $this->lastBody()['user']['password']);
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
