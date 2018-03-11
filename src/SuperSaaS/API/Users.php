<?php namespace SuperSaaS\API;

use SuperSaaS\Models;

class Users extends BaseApi
{
    public function getList($form=NULL, $limit=NULL, $offset=NULL) {
        $path = $this->userPath();
        $query = array(
            'form' => empty($form) ? NULL : 'true',
            'limit' => empty($limit) ? NULL : $this->validateNumber($limit),
            'offset' => empty($offset) ? NULL : $this->validateNumber($offset)
        );
        $res = $this->client->get($path, $query);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\User($attributes);
        }
        return $arr;
    }

    public function get($user_id)
    {
        $path = $this->userPath($user_id);
        $res = $this->client->get($path);
        return new Models\User($res);
    }

    public function create($attributes, $user_id=NULL, $webhook=NULL)
    {
        $path = $this->userPath($user_id);
        $query = array('webhook' => empty($webhook) ? NULL : 'true');
        $params = array(
            'user' => array(
                'name' => $this->validatePresent($attributes['name']),
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'super_field' => $attributes['super_field'],
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : NULL,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : NULL
            )
        );
        $res = $this->client->post($path, $params, $query);
        return new Models\User($res);
    }

    public function update($user_id, $attributes) {
        $path = $this->userPath($this->validateId($user_id));
        $query = array('webhook' => empty($webhook) ? NULL : 'true');
        $params = array(
            'user' => array(
                'name' => $this->validatePresent($attributes['name']),
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'super_field' => $attributes['super_field'],
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : NULL,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : NULL
            )
        );
        return $this->client->put($path, $params, $query);
    }

    public function delete($user_id)
    {
        $path = $this->userPath($this->validateId($user_id));
        return $this->client->delete($path);
    }

    private function userPath($id=NULL)
    {
        if (empty($id)) {
            return "/users";
        } else {
            return "/users/" . $id;
        }
    }
}