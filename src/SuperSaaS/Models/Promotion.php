<?php namespace SuperSaaS\Models;

class Promotion extends BaseModel
{
    public $id;

    public $code;

    public $description;

    public $usage;

    public function __construct($attributes=array())
    {
        $this->id = $this->issetAttr($attributes, 'id');
        $this->code = $this->issetAttr($attributes, 'code');
        $this->description = $this->issetAttr($attributes, 'description');
        $this->usage = $this->issetAttr($attributes, 'usage');
    }
}