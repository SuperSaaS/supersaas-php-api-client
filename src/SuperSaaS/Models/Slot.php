<?php namespace SuperSaaS\Models;

class Slot extends BaseModel
{
    public $count;
    public $description;
    public $finish;
    public $id;
    public $location;
    public $name;
    public $start;
    public $title;


    /**
     * @var \SuperSaaS\Models\Appointment[]
     */
    public $bookings;

    public function __construct($attributes=array())
    {
        $this->count = $this->issetAttr($attributes, 'count');
        $this->description = $this->issetAttr($attributes, 'description');
        $this->finish = $this->issetAttr($attributes, 'finish');
        $this->id = $this->issetAttr($attributes, 'id');
        $this->location = $this->issetAttr($attributes, 'location');
        $this->name = $this->issetAttr($attributes, 'name');
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