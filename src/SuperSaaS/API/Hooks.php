<?php namespace SuperSaaS\API;

use SuperSaaS\SSS_Exception;

/**
 * Manage webhooks.
 * Events: U user created, M user modified, N new appointment, C changed appointment, R reminder,
 * F follow-up, S form submitted, O form changed, H mail, P credit purchase.
 */
class Hooks extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function getList(): array
    {
        $res = $this->client->get('/hooks');
        return is_array($res) ? $res : array();
    }

    /**
     * Returns array('id' => hook id, 'parent_id' => parent id).
     * $parentId is the schedule id for events N/C/R/F, the super form id for S/O, and any
     * non-blank value for U/M/H/P (the server uses the account).
     * @throws SSS_Exception
     */
    public function create($targetUrl, $event, $parentId)
    {
        $params = array(
            'target_url' => $this->validatePresent($targetUrl),
            'event' => $this->validatePresent($event),
            'parent_id' => $this->validateId($parentId),
        );
        return $this->client->post('/hooks', $params);
    }

    /**
     * @throws SSS_Exception
     */
    public function delete($hookId, $parentId)
    {
        $query = array(
            'id' => $this->validateId($hookId),
            'parent_id' => $this->validateId($parentId),
        );
        return $this->client->delete('/hooks', array(), $query);
    }
}
