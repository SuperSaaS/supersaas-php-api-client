<?php use PHPUnit\Framework\TestCase;

class GroupsUnitTest extends TestCase
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

    public function testList()
    {
        $this->assertNotNull($this->client->groups->list());
    }
}