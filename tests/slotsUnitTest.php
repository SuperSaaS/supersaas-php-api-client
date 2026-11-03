<?php
namespace SuperSaaS\Tests;

use PHPUnit\Framework\TestCase;
use SuperSaaS\Configuration;
use SuperSaaS\Client;
use SuperSaaS\SSS_Exception;

class SlotsUnitTest extends TestCase
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
        $start = date('Y-m-d H:i:s');
        $this->assertSame(array(), $this->client->slots->getList(12345, $start, null, 10, 5));
        $this->assertSame('GET', $this->client->lastRequest['method']);
    }

    public function testGet()
    {
        $this->assertInstanceOf('SuperSaaS\Models\Slot', $this->client->slots->get(12345, 67890));
        $this->assertSame('GET', $this->client->lastRequest['method']);
    }

    public function testCreate()
    {
        $this->assertNotNull($this->client->slots->create(12345, $this->slotAttributes()));
        $this->assertSame('POST', $this->client->lastRequest['method']);
        $content = json_decode($this->client->lastRequest['content'], true);
        $this->assertSame('Yoga', $content['slot']['title']);
        $this->assertSame('-', $content['slot']['capacity']);
        $this->assertArrayNotHasKey('unknown', $content['slot']);
    }

    public function testUpdate()
    {
        $this->assertNotNull($this->client->slots->update(12345, 67890, array('capacity' => 0)));
        $this->assertSame('PUT', $this->client->lastRequest['method']);
        $content = json_decode($this->client->lastRequest['content'], true);
        $this->assertSame(array('capacity' => 0), $content['slot']);
    }

    public function testDelete()
    {
        $this->assertNotNull($this->client->slots->delete(12345, 67890));
        $this->assertSame('DELETE', $this->client->lastRequest['method']);
    }

    public function testInvalidIds()
    {
        $this->expectException(SSS_Exception::class);
        $this->client->slots->get('abc', 67890);
    }

    public function testInvalidDatetime()
    {
        $this->expectException(SSS_Exception::class);
        $this->client->slots->create(12345, array('start' => 'tomorrow'));
    }

    public function testSlotModel()
    {
        $slot = new \SuperSaaS\Models\Slot(array('id' => 1, 'title' => 'Yoga', 'capacity' => 10, 'price' => 5, 'color' => '#ff0000', 'level' => 2, 'waiting' => 0));
        $this->assertSame(10, $slot->capacity);
        $this->assertSame('#ff0000', $slot->color);
        $this->assertSame(5, $slot->price);
    }

    private function slotAttributes()
    {
        return array(
            'start' => '2018-01-31 10:00:00',
            'finish' => '2018-01-31 11:00:00',
            'title' => 'Yoga',
            'description' => 'Testing.',
            'location' => 'Room 1',
            'capacity' => '-',
            'price' => '12.50',
            'color' => '#ff0000',
            'unknown' => 'ignored'
        );
    }
}
