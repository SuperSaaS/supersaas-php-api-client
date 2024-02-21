<?php use PHPUnit\Framework\TestCase;

class PromotionsUnitTest extends TestCase
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

    public function tesList()
    {
        $this->assertNotNull($this->client->promotions->getList(10, 10));
    }

    public function testPromotion() {
        $this->assertNotNull($this->client->promotions->promotion("12345"));
    }

    public function testDuplicatePromotionCode() {
        $this->assertNotNull($this->client->promotions->duplicatePromotionCode("newid", "templateid"));
    }
}