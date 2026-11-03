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

    /**
     * @throws SSS_Exception
     */
    public function get($user_id, $form=null): Models\User
    {
        $path = $this->_userPath($user_id);
        $query = array(
            'form' => empty($form) ? null : 'true'
        );
        $res = $this->client->get($path, $query);
        return new Models\User($res);
    }

    /**
     * @param  array           $attributes User attributes, 'name' is required
     * @param  int|string|null $user_id    Optional id or foreign key (e.g. '1234fk')
     * @param  bool|null       $webhook    Trigger webhooks connected to the account
     * @param  string|null     $duplicate  'ignore' or 'raise'
     * @return mixed URL of the new user, or the decoded response body
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
                'name' => $this->validatePresent($attributes['name'] ?? null),
                'email' => $attributes['email'] ?? null,
                'password' => $attributes['password'] ?? null,
                'full_name' => $attributes['full_name'] ?? null,
                'address' => $attributes['address'] ?? null,
                'mobile' => $attributes['mobile'] ?? null,
                'phone' => $attributes['phone'] ?? null,
                'timezone' => $attributes['timezone'] ?? null,
                'country' => $attributes['country'] ?? null,
                'field_1' => $attributes['field_1'] ?? null,
                'field_2' => $attributes['field_2'] ?? null,
                'super_field' => $attributes['super_field'] ?? null,
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : null,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : null,
                'group' => isset($attributes['group']) ? $this->validateNumber($attributes['group']) : null
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
                'name' => $this->validateName($attributes['name'] ?? null),
                'email' => $attributes['email'] ?? null,
                'password' => $attributes['password'] ?? null,
                'full_name' => $attributes['full_name'] ?? null,
                'address' => $attributes['address'] ?? null,
                'mobile' => $attributes['mobile'] ?? null,
                'phone' => $attributes['phone'] ?? null,
                'timezone' => $attributes['timezone'] ?? null,
                'country' => $attributes['country'] ?? null,
                'field_1' => $attributes['field_1'] ?? null,
                'field_2' => $attributes['field_2'] ?? null,
                'super_field' => $attributes['super_field'] ?? null,
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : null,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : null,
                'group' => isset($attributes['group']) ? $this->validateNumber($attributes['group']) : null
            )
        );

        $params['user'] = array_filter(
            $params['user'], function ($value) {
                return $value !== null;
            }
        );

        return $this->client->put($path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function delete($user_id, $webhook=null)
    {
        $path = $this->_userPath($this->validateId($user_id));
        $query = array('webhook' => empty($webhook) ? null : 'true');
        return $this->client->delete($path, array(), $query);
    }

    public function fieldList(): array
    {
        return $this->fetchFieldLists('/field_list');
    }

    public function inputFieldList(): array
    {
        return $this->fetchFieldLists('/input_field_list');
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