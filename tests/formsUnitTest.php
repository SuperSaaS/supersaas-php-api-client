<?php use PHPUnit\Framework\TestCase;

class FormsUnitTest extends TestCase
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

    public function testGetList()
    {
        $from = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->forms->getList(12345, $from));
    }

    public function testGet() {
        $this->assertNotNull($this->client->forms->get(12345));
    }

    public function testForms() {
        $this->assertNotNull($this->client->forms->forms());
    }
}