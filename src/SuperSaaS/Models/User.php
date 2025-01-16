<?php namespace SuperSaaS\Models;

class User extends BaseModel
{
    public $address;
    public $country;
    public $createdOn;
    public $credit;
    public $email;
    public $field1;
    public $field2;
    public $fk;
    public $fullName;
    public $id;
    public $mobile;
    public $name;
    public $phone;
    public $role;
    public $superField;
    public $timezone;
    public $updatedOn;
    public $group;
    public $webhook;

    /**
     * @var \SuperSaaS\Models\Form
     */
    public $form;

    /**
     * @param $attributes
     */
    public function __construct($attributes=array())
    {
        $this->address = $this->issetAttr($attributes, 'address');
        $this->country = $this->issetAttr($attributes, 'country');
        $this->createdOn = $this->issetAttr($attributes, 'created_on');
        $this->credit = $this->issetAttr($attributes, 'address');
        $this->email = $this->issetAttr($attributes, 'email');
        $this->field1 = $this->issetAttr($attributes, 'field_1');
        $this->field2 = $this->issetAttr($attributes, 'field_2');
        $this->fk = $this->issetAttr($attributes, 'fk');
        $this->fullName = $this->issetAttr($attributes, 'full_name');
        $this->id = $this->issetAttr($attributes, 'id');
        $this->mobile = $this->issetAttr($attributes, 'mobile');
        $this->name = $this->issetAttr($attributes, 'name');
        $this->phone = $this->issetAttr($attributes, 'phone');
        $this->role = $this->issetAttr($attributes, 'role');
        $this->superField = $this->issetAttr($attributes, 'super_field');
        $this->updatedOn = $this->issetAttr($attributes, key: 'updated_on');
        $this->timezone = $this->issetAttr($attributes, key: 'timezone');
        $this->group = $this->issetAttr($attributes, 'group');
        $this->webhook = $this->issetAttr($attributes, 'webhook');

        $this->errors = $this->issetAttr($attributes, 'errors');

        if (!empty($attributes['form'])) {
            $this->form = new Form($attributes['form']);
        }
    }
}