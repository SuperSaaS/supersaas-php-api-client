<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Groups extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function list(): array
    {
        $path = '/groups';
        $response = $this->client->get($path);

        $groups = [];
        foreach ($response as $attributes) {
            $groups[] = new Models\Group($attributes);
        }

        return $groups;
    }
}