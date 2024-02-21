<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Forms extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function getList($form_id, $from_time = null, $user = null): array
    {
        $path = "/forms";
        $query = array('form_id' => $this->validateId($form_id));
        if ($from_time) {
            $query['from'] = $this->validateDatetime($from_time);
        }
        if ($user || $user == 0) {
            $query['user'] = $this->validateUser($user);
        }
        $res = $this->client->get($path, $query);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\Form($attributes);
        }
        return $arr;
    }

    /**
     * @throws SSS_Exception
     */
    public function get($form_id): Models\Form
    {
        $path = "/forms";
        $query = array('id' => $this->validateId($form_id));
        $res = $this->client->get($path, $query);
        return new Models\Form($res);
    }

    public function forms(): array
    {
        $path = '/super_forms';
        $res = $this->client->get($path);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\SuperForm($attributes);
        }
        return $arr;
    }
}