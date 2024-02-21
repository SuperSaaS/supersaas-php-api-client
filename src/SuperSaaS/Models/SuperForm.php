<?php namespace SuperSaaS\Models;

class SuperForm extends BaseModel
{
    public $id;

    public $name;

    public function __construct($attributes=array())
    {
        $this->id = $this->issetAttr($attributes, 'id');
        $this->name = $this->issetAttr($attributes, 'name');

        $this->errors = $this->issetAttr($attributes, 'errors');
    }
}