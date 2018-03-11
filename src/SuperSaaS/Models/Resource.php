<?php namespace SuperSaaS\Models;

class Resource extends BaseModel
{
    public $id;
    public $name;

    public function __construct ($attributes=array()) {
        $this->id = $attributes['id'];
        $this->name = $attributes['name'];
    }
}