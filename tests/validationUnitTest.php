<?php
namespace SuperSaaS\Tests;

use PHPUnit\Framework\TestCase;
use SuperSaaS\Client;
use SuperSaaS\Configuration;
use SuperSaaS\SSS_Exception;

/**
 * Locks down the validation error messages, which are part of the public contract.
 */
class ValidationUnitTest extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $config = new Configuration;
        $config->account_name = 'accnt';
        $config->api_key = 'xxxxxxxxxxxxxxxxxxxxxx';
        $config->dry_run = true;
        $this->client = new Client($config);
    }

    public function testInvalidId()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Invalid id parameter: abc. Provide a integer value.');
        $this->client->appointments->get('abc', 1);
    }

    public function testInvalidDatetime()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage("Invalid datetime parameter: tomorrow. Provide a DateTime object or formatted 'YYYY-DD-MM HH:MM:SS' string.");
        $this->client->appointments->available(12345, 'tomorrow');
    }

    public function testInvalidPromotionCode()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Required parameter promotional code not found or contains other than alphanumeric characters.');
        $this->client->promotions->promotion('not valid!');
    }

    public function testInvalidDuplicate()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage("Required parameter duplicate can only be 'ignore' or 'raise'.");
        $this->client->users->create(array('name' => 'Test'), null, null, 'maybe');
    }

    public function testInvalidNotFound()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage("Required parameter notfound can only be 'error' or 'ignore'.");
        $this->client->users->update(12345, array('name' => 'Test'), null, 'maybe');
    }

    public function testMissingAccountName()
    {
        $this->client->account_name = '';
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Account name not configured. Call `SuperSaaS_Client.configure`.');
        $this->client->get('/schedules');
    }

    public function testMissingApiKey()
    {
        $this->client->api_key = '';
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Account api key not configured. Call `SuperSaaS_Client.configure`.');
        $this->client->get('/schedules');
    }

    public function testInvalidHttpMethod()
    {
        $this->expectException(SSS_Exception::class);
        $this->expectExceptionMessage('Invalid HTTP Method: PATCH. Only `GET`, `POST`, `PUT`, `DELETE` supported.');
        $this->client->request('PATCH', '/schedules');
    }

    public function testRequestBodyDropsEmptyValues()
    {
        $this->client->put('/users/12345', array('user' => array('name' => 'Test', 'full_name' => '', 'email' => null)));
        $body = json_decode($this->client->lastRequest['content'], true);
        $this->assertSame(array('name' => 'Test'), $body['user']);
        $this->assertSame('accnt', $body['account_name']);
    }

    public function testDryRunDoesNotPrint()
    {
        $this->expectOutputString('');
        $this->client->schedules->getList();
    }
}
