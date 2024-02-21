<?php namespace SuperSaaS\API;

use SuperSaaS\Models;
use SuperSaaS\SSS_Exception;

class Appointments extends BaseApi
{
    /**
     * @throws SSS_Exception
     */
    public function agenda($schedule_id, $user_id, $from_time = null, $slot=false): array
    {
        $path = '/agenda/' . $this->validateId($schedule_id);
        $query = array(
            'user' => $this->validatePresent($user_id),
            'from' => empty($from_time) ? null : $this->validateDatetime($from_time),
            'slot' => empty($slot) ? null : true
        );
        $res = $this->client->get($path, $query);
        return $this->_mapSlotOrBookings($res, $slot);
    }

    /**
     * @throws SSS_Exception
     * One can achieve the same result by using agenda
     */
    public function agendaSlots($schedule_id, $user_id, $from_time = null): array
    {
        $path = '/agenda/' . $this->validateId($schedule_id);
        $query = array(
            'user' => $this->validatePresent($user_id),
            'from' => empty($from_time) ? null : $this->validateDatetime($from_time),
            'slot' => 'true'
        );
        $res = $this->client->get($path, $query);
        return $this->_mapSlotOrBookings($res, true);
    }

    /**
     * @throws SSS_Exception
     */
    public function available($schedule_id, $from_time = null, $length_minutes = null, $resource = null, $full = null, $limit = null): array
    {
        $path = '/free/' . $this->validateId($schedule_id);
        $query = array(
            'length' => empty($length_minutes) ? null : $this->validateNumber($length_minutes),
            'from' => empty($from_time) ? null : $this->validateDatetime($from_time),
            'resource' => $resource,
            'full' => empty($full) ? null : 'true',
            'maxresults' => empty($limit) ? null : $this->validateNumber($limit)
        );
        $res = $this->client->get($path, $query);
        return $this->_mapSlotOrBookings($res);
    }

    /**
     * @throws SSS_Exception
     */
    public function getList($schedule_id, $form=null, $start_time=null, $limit=null): array
    {
        $path = '/bookings';
        $query = array(
            'schedule_id' => $this->validateId($schedule_id),
            'form' => empty($form) ? null : 'true',
            'limit' => empty($limit) ? null : $this->validateNumber($limit),
            'start' => empty($start_time) ? null : $this->validateDatetime($start_time)
        );
        $res = $this->client->get($path, $query);
        return $this->_mapSlotOrBookings($res);
    }

    /**
     * @throws SSS_Exception
     */
    public function get($schedule_id, $appointment_id): Models\Appointment
    {
        $params = array('schedule_id' => $this->validateId($schedule_id));
        $path = '/bookings/' . $this->validateId($appointment_id);
        $res = $this->client->get($path, $params);
        return new Models\Appointment($res);
    }

    /**
     * @throws SSS_Exception
     */
    public function create($schedule_id, $attributes, $user_id, $form=null, $webhook=null)
    {
        $path = '/bookings';
        $query = array('webhook' => empty($webhook) ? null : 'true');
        $params = array(
            'schedule_id' => $this->validateId($schedule_id),
            'user_id' => empty($user_id) ? null : $this->validateId($user_id),
            'form' => empty($form) ? null : 'true',
            'booking' => array(
                'start' => $attributes['start'],
                'finish' => $attributes['finish'],
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'field_1_r' => $attributes['field_1_r'],
                'field_2_r' => $attributes['field_2_r'],
                'super_field' => $attributes['super_field'],
                'resource_id' => $attributes['resource_id'],
                'slot_id' => $attributes['slot_id']
            )
        );
        return $this->client->post($path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function update($schedule_id, $appointment_id, $attributes, $form=null, $webhook=null)
    {
        $path = '/bookings/' . $this->validateId($appointment_id);
        $query = array('webhook' => empty($webhook) ? null : 'true');
        $params = array(
            'schedule_id' => $this->validateId($schedule_id),
            'form' => empty($form) ? null : 'true',
            'webhook' => $attributes['webhook'],
            'booking' => array(
                'start' => $attributes['start'],
                'finish' => $attributes['finish'],
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'field_1_r' => $attributes['field_1_r'],
                'field_2_r' => $attributes['field_2_r'],
                'super_field' => $attributes['super_field'],
                'resource_id' => $attributes['resource_id'],
                'slot_id' => $attributes['slot_id']
            )
        );
        $params['booking'] = array_filter(
            $params['booking'], function ($value) {
                return $value !== null;
            }
        );
        return $this->client->post($path, $params, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function delete($schedule_id, $appointment_id)
    {
        $path = sprintf("/bookings/%s", $this->validateId($appointment_id));
        $query = array('schedule_id' => $schedule_id);
        return $this->client->delete($path, $query);
    }

    /**
     * @throws SSS_Exception
     */
    public function changes($schedule_id, $from_time = null, $to=null, $slot=false, $user=null, $limit=null, $offset=null): array
    {
        $path = '/changes/' . $this->validateId($schedule_id);
        $query = $this->buildParam([], $from_time, $to, $slot, $user, $limit, $offset);
        $res = $this->client->get($path, $query);
        return $this->_mapSlotOrBookings($res, $slot);
    }

    /**
     * @throws SSS_Exception
     */
    public function changesSlots($schedule_id, $from_time = null): array
    {
        $path = '/changes/' . $this->validateId($schedule_id);
        $query = array(
            'slot' => 'true',
            'from' => empty($from_time) ? null : $this->validateDatetime($from_time)
        );
        $res = $this->client->get($path, $query);
        return $this->_mapSlotOrBookings($res, true);
    }

    /**
     * @throws SSS_Exception
     */
    public function range($scheduleId, $today = false, $fromTime = null, $to = null, $slot = false, $user = null, $resourceId = null, $serviceId = null, $limit = null, $offset = null): array
    {
        $path = "/range/" . $this->validateId($scheduleId);
        $params = [];

        $params = $this->buildParam($params, $fromTime, $to, $slot, $user, $limit, $offset, $resourceId, $serviceId);
        if ($today) {
            $params['today'] = true;
        }

        $response = $this->client->get($path, $params);

        return $this->_mapSlotOrBookings($response);
    }

    /**
     * @throws SSS_Exception, recommended to use range function
     */
    public function listAppointments($schedule_id, $today=false, $from_time = null, $to = null, $slot = false): array
    {
            $path = '/range/' . $this->validateId($schedule_id);
            $query = array(
                'today' => $today ? $today : null,
                'from' => empty($from_time) ? null : $this->validateDatetime($from_time),
                'to' => empty($to) ? null : $this->validateDatetime($to),
                'slot' => $slot ? $slot : null,
            );
            $res = $this->client->get($path, $query);
            return $this->_mapSlotOrBookings($res);
    }

    private function _mapSlotOrBookings($obj, $slot=false): array
    {
        if (isset($obj['slots'])) {
            return array_map(
                function ($attributes) {
                    return new Models\Slot($attributes);
                }, $obj['slots']
            );
        } else if (isset($obj['bookings'])) {
            return array_map(
                function ($attributes) {
                    return new Models\Appointment($attributes);
                }, $obj['bookings']
            );
        } else if (is_array($obj)) {
            if ($slot) {
                return array_map(
                    function ($attributes) {
                        return new Models\Slot($attributes);
                    }, $obj
                );
            } else {
                return array_map(
                    function ($attributes) {
                        return new Models\Appointment($attributes);
                    }, $obj
                );
            }
        } else {
            return [];
        }
    }

    /**
     * @throws SSS_Exception
     */
    function buildParam($params, $fromTime, $to, $slot, $user, $limit, $offset, $resourceId = null, $serviceId = null)
    {
        if ($fromTime) {
            $params['from'] = $this->validateDatetime($fromTime);
        }
        if ($to) {
            $params['to'] = $this->validateDatetime($to);
        }
        if ($slot) {
            $params['slot'] = 'true';
        }
        if ($user !== null) {
            $params['user'] = $this->validateUser($user);
        }
        if ($limit) {
            $params['limit'] = $this->validateNumber($limit);
        }
        if ($offset) {
            $params['offset'] = $this->validateNumber($offset);
        }
        if ($resourceId !== null) {
            $params['resource_id'] = $this->validateId($resourceId);
        }
        if ($serviceId !== null) {
            $params['service_id'] = $this->validateId($serviceId);
        }
        return $params;
    }
}