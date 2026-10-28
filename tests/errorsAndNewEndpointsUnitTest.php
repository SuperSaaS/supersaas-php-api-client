<?php
namespace SuperSaaS\Tests;

use PHPUnit\Framework\TestCase;
use SuperSaaS\Configuration;
use SuperSaaS\Client;
use SuperSaaS\SSS_Exception;

class ErrorsAndNewEndpointsUnitTest extends TestCase
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

    public function testExceptionKeepsMessageAndDefaults()
    {
        $e = new SSS_Exception('Required parameter is missing.');
        $this->assertSame('Required parameter is missing.', $e->getMessage());
        $this->assertSame(0, $e->getCode());
        $this->assertSame(array(), $e->getErrors());
    }

    public function testExceptionCarriesStatusAndErrors()
    {
        $errors = array(array('status' => '404', 'title' => 'Not found'));
        $e = new SSS_Exception('HTTP Request Error: 404 http://x/api/users/1.json', 404, null, $errors);
        $this->assertSame('HTTP Request Error: 404 http://x/api/users/1.json', $e->getMessage());
        $this->assertSame(404, $e->getCode());
        $this->assertSame($errors, $e->getErrors());
    }

    public function testExistingValidationMessageUnchanged()
    {
        try {
            $this->client->users->delete('abc');
            $this->client->slots->get('abc', 1);
            $this->fail('Expected exception');
        } catch (SSS_Exception $e) {
            $this->assertSame('Invalid id parameter: abc. Provide a integer value.', $e->getMessage());
            $this->assertSame(0, $e->getCode());
            $this->assertSame(array(), $e->getErrors());
        }
    }

    public function testParseErrors()
    {
        $parse = new \ReflectionMethod(Client::class, '_parseErrors');
        $this->assertSame(
            array(array('status' => '404', 'title' => 'Not found')),
            $parse->invoke($this->client, '{"errors":[{"status":"404","title":"Not found"}]}')
        );
        $this->assertSame(array('msg'), $parse->invoke($this->client, '{"errors":["msg"]}'));
        $this->assertSame(array(), $parse->invoke($this->client, ''));
        $this->assertSame(array(), $parse->invoke($this->client, false));
        $this->assertSame(array(), $parse->invoke($this->client, '<html>Not found</html>'));
        $this->assertSame(array(), $parse->invoke($this->client, '{"other":1}'));
    }

    public function testPing()
    {
        $this->assertTrue($this->client->ping());
        $this->assertSame('GET', $this->client->lastRequest['method']);
    }

    public function testHooksCreate()
    {
        $this->assertIsArray($this->client->hooks->create('https://www.example.com/hook', 'U', 12345));
        $this->assertSame('POST', $this->client->lastRequest['method']);
        $content = json_decode($this->client->lastRequest['content'], true);
        $this->assertSame('https://www.example.com/hook', $content['target_url']);
        $this->assertSame('U', $content['event']);
        $this->assertSame(12345, $content['parent_id']);
    }

    public function testHooksDelete()
    {
        $this->client->hooks->delete(7, 12345);
        $this->assertSame('DELETE', $this->client->lastRequest['method']);
    }

    public function testHooksGetList()
    {
        $this->assertSame(array(), $this->client->hooks->getList());
        $this->assertSame('GET', $this->client->lastRequest['method']);
    }

    public function testHooksInvalid()
    {
        $this->expectException(SSS_Exception::class);
        $this->client->hooks->delete('abc', 1);
    }

    public function testInputFieldLists()
    {
        $this->assertSame(array(), $this->client->schedules->inputFieldList(12345));
        $this->assertSame('GET', $this->client->lastRequest['method']);
        $this->assertSame(array(), $this->client->users->inputFieldList());
    }

    public function testFieldListRequired()
    {
        $f = new \SuperSaaS\Models\FieldList(array('name' => 'x', 'type' => 'text', 'required' => true));
        $this->assertTrue($f->required);
    }
}
