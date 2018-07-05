<?php

class AppointmentsUnitTest extends PHPUnit_Framework_TestCase
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
        $this->assertNotNull($this->client->appointments->get(12345, 67890));
    }

    public function testGetList() {
        $limit = 10;
        $start = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->appointments->getList(12345, true, $start, $limit));
    }

    public function testCreate() {
        $this->assertNotNull($this->client->appointments->create(12345, $this->appointmentAttributes(), 67890, true, true));
    }

    public function testUpdate() {
        $this->assertNotNull($this->client->appointments->update(12345, 67890, $this->appointmentAttributes()));
    }

    public function testAgenda() {
        $from = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->appointments->agenda(12345, 67890, $from));
    }

    public function testAgendaSlots() {
        $from = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->appointments->agendaSlots(12345, 67890, $from));
    }

    public function testAvailable() {
        $from = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->appointments->available(12345, $from));
    }

    public function testAvailableFull() {
        $length_minutes = 15;
        $limit = 10;
        $from = "2017-01-31 14:30:00";
        $resource = 'MyResource';
        $this->assertNotNull($this->client->appointments->available(12345, $from, $length_minutes, $resource, true, $limit));
    }

    public function testChanges() {
        $from = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->appointments->changes(12345, $from));
    }

    public function testChangesSlots() {
        $from = date('Y-m-d H:i:s');
        $this->assertNotNull($this->client->appointments->changesSlots(12345, $from));
    }

    public function testDelete() {
        $this->assertNotNull($this->client->appointments->delete(12345, 67890));
    }

    private function appointmentAttributes() {
        return array(
            'description' => 'Testing.',
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
            'field_1_r' => 'f 1 r',
            'field_2_r' => 'f 2 r',
            'super_field' => 'sf'
        );
    }
}