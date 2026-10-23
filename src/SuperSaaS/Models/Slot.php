<?php namespace SuperSaaS\Models;

class Slot extends BaseModel
{
    public $capacity;
    public $color;
    public $count;
    public $description;
    public $finish;
    public $id;
    public $level;
    public $location;
    public $name;
    public $price;
    public $start;
    public $title;
    public $waiting;


    /**
     * @var \SuperSaaS\Models\Appointment[]
     */
    public $bookings;

    public function __construct($attributes=array())
    {
        $this->capacity = $this->issetAttr($attributes, 'capacity');
        $this->color = $this->issetAttr($attributes, 'color');
        $this->count = $this->issetAttr($attributes, 'count');
        $this->description = $this->issetAttr($attributes, 'description');
        $this->finish = $this->issetAttr($attributes, 'finish');
        $this->id = $this->issetAttr($attributes, 'id');
        $this->level = $this->issetAttr($attributes, 'level');
        $this->location = $this->issetAttr($attributes, 'location');
        $this->name = $this->issetAttr($attributes, 'name');
        $this->price = $this->issetAttr($attributes, 'price');
        $this->start = $this->issetAttr($attributes, 'start');
        $this->title = $this->issetAttr($attributes, 'title');
        $this->waiting = $this->issetAttr($attributes, 'waiting');

        $this->errors = $this->issetAttr($attributes, 'errors');

        if (!empty($attributes['bookings'])) {
            $this->bookings = array();
            foreach ($attributes['bookings'] as $booking) {
                $this->bookings[] = new Appointment($booking);
            }
        }
    }
}