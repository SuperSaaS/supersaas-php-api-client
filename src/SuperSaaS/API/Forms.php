<?php namespace SuperSaaS\API;

use SuperSaaS\Models;

class Forms extends BaseApi
{
    public function getList($form_id, $from_time = NULL)
    {
        $path = "/forms";
        $query = array('form_id' => $this->validateId($form_id));
        if ($from_time) {
            $params['from'] = $this->validateDatetime($from_time);
        }
        $res = $this->client->get($path, $query);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\Form($attributes);
        }
        return $arr;
    }

    public function get($form_id) {
        $path = "/forms";
        $query = array('id' => $this->validateId($form_id));
        $res = $this->client->get($path, $query);
        return new Models\Form($res);
    }
}