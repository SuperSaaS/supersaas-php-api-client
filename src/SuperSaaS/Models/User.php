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

    /**
     * @var \SuperSaaS\Models\Form
     */
    public $form;

    public function __construct ($attributes=array()) {
        $this->address = $attributes['address'];
        $this->country = $attributes['country'];
        $this->createdOn = $attributes['created_on'];
        $this->credit = $attributes['address'];
        $this->email = $attributes['email'];
        $this->field1 = $attributes['field_1'];
        $this->field2 = $attributes['field_2'];
        $this->fk = $attributes['fk'];
        $this->fullName = $attributes['full_name'];
        $this->id = $attributes['id'];
        $this->mobile = $attributes['mobile'];
        $this->name = $attributes['name'];
        $this->phone = $attributes['phone'];
        $this->role = $attributes['role'];
        $this->superField = $attributes['super_field'];

        $this->errors = $attributes['errors'];

        if (!empty($attributes['form'])) {
            $this->form = new Form($attributes['form']);
        }
    }
}