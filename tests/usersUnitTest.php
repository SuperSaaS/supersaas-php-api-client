<?php

class UsersUnitTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $config = new SuperSaas\Configuration;
        $config->account_name = 'accnt';
        $config->password = 'pwd';
        $config->dry_run = true;
        $this->client = new SuperSaas\Client($config);
    }

    public function testGet()
    {
        $this->assertNotNull($this->client->users->get(12345));
    }

    public function testGetList() {
        $limit = 10;
        $offset = 0;
        $this->assertNotNull($this->client->users->getList(12345, $limit, $offset));
    }

    public function testCreate() {
        $this->assertNotNull($this->client->users->create($this->userAttributes()));
    }

    public function testCreateFk() {
        $this->assertNotNull($this->client->users->create($this->userAttributes(), '123FK'));
    }

    public function testUpdate() {
        $this->assertNotNull($this->client->users->update(12345, $this->userAttributes(), true));
    }

    public function testDelete() {
        $this->assertNotNull($this->client->users->delete(12345));
    }

    private function userAttributes() {
        return array(
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'full_name' => 'Tester Test',
            'address' => '123 St, City',
            'mobile' => '555-5555',
            'phone' => '555-5555',
            'country' => 'FR',
            'field_1' => 'f 1',
            'field_2' => 'f 2',
            'super_field' => 'sf',
            'credit' => 10,
            'role' => 3
        );
    }
}