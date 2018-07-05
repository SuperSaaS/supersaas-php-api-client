<?php namespace SuperSaaS\API;

use SuperSaaS\Models;

class Appointments extends BaseApi
{
    public function agenda ($schedule_id, $user_id, $from_time = NULL)
    {
        $path = '/agenda/' . $this->validateId($schedule_id);
        $query = array(
            'user' => $this->validatePresent($user_id),
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time)
        );
        $res = $this->client->get($path, $query);
        return $this->mapSlotOrBookings($res);
    }

    public function agendaSlots ($schedule_id, $user_id, $from_time = NULL)
    {
        $path = '/agenda/' . $this->validateId($schedule_id);
        $query = array(
            'user' => $this->validatePresent($user_id),
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time),
            'slot' => 'true'
        );
        $res = $this->client->get($path, $query);
        return $this->mapSlotOrBookings($res, true);
    }

    public function available($schedule_id, $from_time = NULL, $length_minutes = NULL, $resource = NULL, $full = NULL, $limit = NULL)  {
        $path = '/free/' . $this->validateId($schedule_id);
        $query = array(
            'length' => empty($length_minutes) ? NULL : $this->validateNumber($length_minutes),
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time),
            'resource' => $resource,
            'full' => empty($full) ? NULL : 'true',
            'maxresults' => empty($limit) ? NULL : $this->validateNumber($limit)
        );
        $res = $this->client->get( $path, $query);
        return $this->mapSlotOrBookings($res);
    }

    public function getList($schedule_id, $form=NULL, $start_time=NULL, $limit=NULL) {
        $path = '/bookings';
        $query = array(
            'schedule_id' => $this->validateId($schedule_id),
            'form' => empty($form) ? NULL : 'true',
            'limit' => empty($limit) ? NULL : $this->validateNumber($limit),
            'start' => empty($start_time) ? NULL : $this->validateDatetime($start_time)
        );
        $res = $this->client->get($path, $query);
        return $this->mapSlotOrBookings($res);
    }

    public function get($schedule_id, $appointment_id)
    {
        $params = array('schedule_id' => $this->validateId($schedule_id));
        $path = '/bookings/' . $this->validateId($appointment_id);
        $res = $this->client->get($path, $params);
        return new Models\Appointment($res);
    }

    public function create($schedule_id, $attributes, $user_id, $form=NULL, $webhook=NULL)
    {
        $path = '/bookings';
        $query = array('webhook' => empty($webhook) ? NULL : 'true');
        $params = array(
            'schedule_id' => $this->validateId($schedule_id),
            'user_id' => empty($user_id) ? NULL : $this->validateId($user_id),
            'form' => empty($form) ? NULL : 'true',
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
        $res = $this->client->post($path, $params, $query);
        return new Models\Appointment($res);
    }

    public function update($schedule_id, $appointment_id, $attributes, $form=NULL, $webhook=NULL)
    {
        $path = '/bookings/' . $this->validateId($appointment_id);
        $query = array('webhook' => empty($webhook) ? NULL : 'true');
        $params = array(
            'schedule_id' => $schedule_id,
            'form' => empty($form) ? NULL : 'true',
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
        $res = $this->client->post($path, $params, $query);
        return new Models\Appointment($res);
    }

    public function delete($schedule_id, $appointment_id)
    {
        $path = '/bookings/' . $this->validateId($appointment_id);
        $query = array('schedule_id' => $schedule_id);
        return $this->client->delete($path, $query);
    }

    public function changes($schedule_id, $from_time = NULL)
    {
        $path = '/changes/' . $this->validateId($schedule_id);
        $query = array(
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time)
        );
        $res = $this->client->get($path, $query);
        return $this->mapSlotOrBookings($res);
    }

    public function changesSlots ($schedule_id, $from_time = NULL)
    {
        $path = '/changes/' . $this->validateId($schedule_id);
        $query = array(
            'slot' => 'true',
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time)
        );
        $res = $this->client->get($path, $query);
        return $this->mapSlotOrBookings($res, true);
    }

    private function mapSlotOrBookings($res, $slot = FALSE) {
        $arr = array();
        if (isset($res["slots"])) {
            $slot = TRUE;
            $res = $res["slots"];
        } elseif (isset($res['bookings'])) {
            $res = $res['bookings'];
        }
        foreach ($res as $attributes) {
            if ($slot) {
                $arr[] = new Models\Slot($attributes);
            } else {
                $arr[] = new Models\Appointment($attributes);
            }
        }
        return $arr;
    }
}