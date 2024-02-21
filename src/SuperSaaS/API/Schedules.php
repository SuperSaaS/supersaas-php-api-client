<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Schedules extends BaseApi
{
    public function getList(): array
    {
        $path = "/schedules";
        $res = $this->client->get($path);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\Schedule($attributes);
        }
        return $arr;
    }

    /**
     * @throws SSS_Exception
     */
    public function resources($schedule_id): array
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

    /**
     * @throws SSS_Exception
     */
    public function fieldList($scheduleId): array
    {
        $path = '/field_list';
        $query = ['schedule_id' => $this->validateId($scheduleId)];

        $response = $this->client->get($path, $query);

        $fieldLists = [];
        foreach ($response as $attributes) {
            $fieldLists[] = new Models\FieldList($attributes);
        }

        return $fieldLists;
    }
}