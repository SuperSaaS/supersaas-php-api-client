<?php namespace SuperSaaS\API;

use SuperSaaS\Models;

class Schedules extends BaseApi
{
    public function getList()
    {
        $path = "/schedules";
        $res = $this->client->get($path);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\Schedule($attributes);
        }
        return $arr;
    }

    public function resources($schedule_id)
    {
        $path = '/resources';
        $query = array(
            'schedule_id' => $this->validateId($schedule_id),
        );
        $res = $this->client->get($path, $query);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\Resource($attributes);
        }
        return $arr;
    }
}