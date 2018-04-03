<?php namespace SuperSaaS\Models;

class BaseModel
{
    public $errors;

    public function issetAttr($attributes, $key) {
      return isset($attributes[$key]) ? $attributes[$key] : NULL;
    }
}