<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Schedules extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
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
        return $this->fetchFieldLists('/field_list', ['schedule_id' => $this->validateId($scheduleId)]);
    }

    /**
     * @throws SSS_Exception
     */
    public function inputFieldList($scheduleId): array
    {
        return $this->fetchFieldLists('/input_field_list', ['schedule_id' => $this->validateId($scheduleId)]);
    }
}
