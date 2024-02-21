<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Users extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function getList($form=null, $limit=null, $offset=null): array
    {
        $path = $this->_userPath();
        $query = array(
            'form' => empty($form) ? null : 'true',
            'limit' => empty($limit) ? null : $this->validateNumber($limit),
            'offset' => empty($offset) ? null : $this->validateNumber($offset)
        );
        $res = $this->client->get($path, $query);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = new Models\User($attributes);
        }
        return $arr;
    }

    public function get($user_id): Models\User
    {
        $path = $this->_userPath($user_id);
        $res = $this->client->get($path);
        return new Models\User($res);
    }

    /**
     * @param  null $user_id
     * @param  null $webhook
     * @param  null $duplicate
     * @return array|mixed|string
     * @throws SSS_Exception
     */
    public function create($attributes, $user_id = null, $webhook = null, $duplicate = null): mixed
    {
        $path = $this->_userPath($user_id);
        $query = array('webhook' => empty($webhook) ? null : 'true');
        if ($duplicate !== null) {
            $query['duplicate'] = $this->validateDuplicate($duplicate);
        }
        $params = array(
            'user' => array(
                'name' => $this->validatePresent($attributes['name']),
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'timezone' => $attributes['timezone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'super_field' => $attributes['super_field'],
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : null,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : null
            )
        );
        return $this->client->post($path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function update($user_id, $attributes, $webhook=null, $notFound=null)
    {
        $path = $this->_userPath($this->validateId($user_id));
        $query = array('webhook' => empty($webhook) ? null : 'true');
        if ($notFound !== null) {
            $query['notfound'] = $this->validateNotFound($notFound);
        }
        $params = array(
            'user' => array(
                'name' => $this->validateName($attributes['name']),
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'timezone' => $attributes['timezone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'super_field' => $attributes['super_field'],
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : null,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : null
            )
        );

        $params['user'] = array_filter(
            $params['user'], function ($value) {
                return $value !== null;
            }
        );

        return $this->client->put($path, $params, $query);
    }

    public function delete($user_id)
    {
        $path = $this->_userPath($this->validateId($user_id));
        return $this->client->delete($path);
    }

    public function fieldList(): array
    {
        $path = '/field_list';
        $res = $this->client->get($path);

        $fieldLists = array();
        foreach ($res as $attributes) {
            $fieldLists[] = new Models\FieldList($attributes);
        }

        return $fieldLists;
    }

    /**
     * @throws SSS_Exception
     */
    private function _userPath($id=null): string
    {
        if (empty($id)) {
            return "/users";
        } else {
            return "/users/" . $this->validateUser($id);
        }
    }
}