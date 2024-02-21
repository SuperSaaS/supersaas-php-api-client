<?php namespace SuperSaaS\API;

use SuperSaaS\Models;

class Groups extends BaseApi
{
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