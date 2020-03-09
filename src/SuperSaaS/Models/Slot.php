<?php namespace SuperSaaS\Models;

class Slot extends BaseModel
{
    public $description;
    public $finish;
    public $id;
    public $location;
    public $start;
    public $title;

    /**
     * @var \SuperSaaS\Models\Appointment[]
     */
    public $bookings;

    public function __construct ($attributes=array()) {
        $this->description = $this->issetAttr($attributes, 'description');
        $this->finish = $this->issetAttr($attributes, 'finish');
        $this->id = $attributes['id'];
        $this->location = $this->issetAttr($attributes, 'location');
        $this->start = $this->issetAttr($attributes, 'start');
        $this->title = $this->issetAttr($attributes, 'title');

        $this->errors = $this->issetAttr($attributes, 'errors');

        if (!empty($attributes['bookings'])) {
            $this->bookings = array();
            foreach ($attributes['bookings'] as $booking) {
                $this->bookings[] = new Appointment($booking);
            }
        }
    }
}