<?php namespace SuperSaaS\Models;

class Group extends BaseModel
{
    public $id;

    public $name;

    public function __construct($attributes=array())
    {
        $this->id = $this->issetAttr($attributes, 'id');
        $this->name = $this->issetAttr($attributes, 'name');
    }
}