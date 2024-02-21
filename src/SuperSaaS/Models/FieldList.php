<?php namespace SuperSaaS\Models;

class FieldList extends BaseModel
{
    public $name;
    public $type;
    public $label;
    public $advanced;

    public $spec;

    public function __construct($attributes=array())
    {
        $this->name = $this->issetAttr($attributes, 'name');
        $this->type = $this->issetAttr($attributes, 'type');
        $this->label = $this->issetAttr($attributes, 'label');
        $this->advanced = $this->issetAttr($attributes, 'advanced');

        $this->errors = $this->issetAttr($attributes, 'errors');

        if (!empty($attributes['spec'])) {
            $value = $attributes['spec'];
            if (is_array($value)) {
                $this->spec = array_map(
                    function ($attributes) {
                        return json_decode($attributes, true);
                    }, $value
                );
            } else {
                $this->spec = $value;
            }
        }
    }
}