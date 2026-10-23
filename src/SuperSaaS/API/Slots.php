<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

/**
 * Manage the slots of a capacity type schedule.
 * Only available for capacity type schedules, the server responds with 405 for other schedule types.
 */
class Slots extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function getList($schedule_id, $start_time = null, $finish = null, $limit = null, $offset = null): array
    {
        $path = '/slots';
        $query = array(
            'schedule_id' => $this->validateId($schedule_id),
            'start' => empty($start_time) ? null : $this->validateDatetime($start_time),
            'finish' => empty($finish) ? null : $this->validateDatetime($finish),
            'limit' => empty($limit) ? null : $this->validateNumber($limit),
            'offset' => empty($offset) ? null : $this->validateNumber($offset)
        );
        $res = $this->client->get($path, $query);
        return array_map(
            function ($attributes) {
                return new Models\Slot($attributes);
            }, is_array($res) ? $res : array()
        );
    }

    /**
     * @throws SSS_Exception
     */
    public function get($schedule_id, $slot_id): Models\Slot
    {
        $query = array('schedule_id' => $this->validateId($schedule_id));
        $path = '/slots/' . $this->validateId($slot_id);
        $res = $this->client->get($path, $query);
        return new Models\Slot($res);
    }

    /**
     * @throws SSS_Exception
     */
    public function create($schedule_id, $attributes)
    {
        $path = '/slots';
        $query = array('schedule_id' => $this->validateId($schedule_id));
        $params = array('slot' => $this->_slotParams($attributes));
        return $this->client->post($path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function update($schedule_id, $slot_id, $attributes)
    {
        $path = '/slots/' . $this->validateId($slot_id);
        $query = array('schedule_id' => $this->validateId($schedule_id));
        $params = array('slot' => $this->_slotParams($attributes));
        return $this->client->put($path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     * Also deletes the bookings on the slot.
     */
    public function delete($schedule_id, $slot_id)
    {
        $path = '/slots/' . $this->validateId($slot_id);
        $query = array('schedule_id' => $this->validateId($schedule_id));
        return $this->client->delete($path, array(), $query);
    }

    /**
     * Capacity: '-' (or an empty value) means unlimited, 0 means no places.
     * @throws SSS_Exception
     */
    private function _slotParams($attributes): array
    {
        $slot = array();
        foreach (array('start', 'finish') as $key) {
            if (isset($attributes[$key])) {
                $slot[$key] = $this->validateDatetime($attributes[$key]);
            }
        }
        foreach (array('title', 'description', 'location', 'capacity', 'price', 'color') as $key) {
            if (isset($attributes[$key])) {
                $slot[$key] = $attributes[$key];
            }
        }
        return $slot;
    }
}
