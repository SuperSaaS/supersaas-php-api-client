<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Promotions extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function list($limit = null, $offset = null): array
    {
        $path = '/promotions';
        $params = [];
        if ($limit !== null) {
            $params['limit'] = $this->validateNumber($limit);
        }
        if ($offset !== null) {
            $params['offset'] = $this->validateNumber($offset);
        }
        $response = $this->client->get($path, $params);

        $promotions = [];
        foreach ($response as $attributes) {
            $promotions[] = new Models\Promotion($attributes);
        }

        return $promotions;
    }

    /**
     * @throws SSS_Exception
     */
    public function promotion($promotionCode): array
    {
        $path = '/promotions';
        $query = ['promotion_code' => $this->validatePromotion($promotionCode)];
        $response = $this->client->get($path, $query);

        $promotions = [];
        foreach ($response as $attributes) {
            $promotions[] = new Models\Promotion($attributes);
        }

        return $promotions;
    }

    /**
     * @throws SSS_Exception
     */
    public function duplicatePromotionCode($promotionCode, $templateCode)
    {
        $path = '/promotions';
        $query = [
            'id' => $this->validatePromotion($promotionCode),
            'template_code' => $this->validatePromotion($templateCode)
        ];
        return $this->client->post($path, $query);
    }
}