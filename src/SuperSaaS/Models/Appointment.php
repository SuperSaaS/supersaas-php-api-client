<?php namespace SuperSaaS\Models;

class Appointment extends BaseModel
{
    public $address;
    public $country;
    public $createdBy;
    public $description;
    public $createdOn;
    public $deleted;
    public $email;
    public $field1;
    public $field2;
    public $field1R;
    public $field2R;
    public $finish;
    public $formId;
    public $fullName;
    public $id;
    public $mobile;
    public $name;
    public $phone;
    public $price;
    public $resName;
    public $resourceId;
    public $role;
    public $scheduleId;
    public $scheduleName;
    public $serviceId;
    public $serviceName;
    public $slotId;
    public $start;
    public $status;
    public $superField;
    public $updatedBy;
    public $updatedOn;
    public $userId;
    public $waitlisted;

    /**
     * @var \SuperSaaS\Models\Form
     */
    public $form;

    /**
     * @var \SuperSaaS\Models\Slot
     */
    public $slot;

    public function __construct ($attributes=array()) {
        $this->address = $attributes['address'];
        $this->country = $attributes['country'];
        $this->createdBy = $attributes['created_by'];
        $this->description = $attributes['description'];
        $this->createdOn = $attributes['created_on'];
        $this->deleted = $attributes['deleted'];
        $this->email = $attributes['email'];
        $this->field1 = $attributes['field_1'];
        $this->field2 = $attributes['field_2'];
        $this->field1R = $attributes['field_1_r'];
        $this->field2R = $attributes['field_2_r'];
        $this->finish = $attributes['finish'];
        $this->formId = $attributes['form_id'];
        $this->fullName = $attributes['full_name'];
        $this->id = $attributes['id'];
        $this->mobile = $attributes['mobile'];
        $this->name = $attributes['name'];
        $this->phone = $attributes['phone'];
        $this->price = $this->issetAttr($attributes, 'price');
        $this->resName = $this->issetAttr($attributes, 'res_name');
        $this->resourceId = $this->issetAttr($attributes, 'resource_id');
        $this->role = $this->issetAttr($attributes, 'role');
        $this->scheduleId = $this->issetAttr($attributes, 'schedule_id');
        $this->scheduleName = $attributes['schedule_name'];
        $this->serviceId = $this->issetAttr($attributes, 'service_id');
        $this->serviceName = $this->issetAttr($attributes, 'service_name');
        $this->slotId = $this->issetAttr($attributes, 'slot_id');
        $this->start = $attributes['start'];
        $this->status = $this->issetAttr($attributes, 'status');
        $this->superField = $this->issetAttr($attributes, 'super_field');
        $this->updatedBy = $this->issetAttr($attributes, 'updated_by');
        $this->updatedOn = $this->issetAttr($attributes, 'updated_on');
        $this->userId = $this->issetAttr($attributes, 'user_id');
        $this->waitlisted = $this->issetAttr($attributes, 'waitlisted');

        $this->errors = $this->issetAttr($attributes, 'errors');

        if (!empty($attributes['form'])) {
            $this->form = new Form($attributes['form']);
        }

        if (!empty($attributes['slot'])) {
            $this->slot = new Slot($attributes['slot']);
        }
    }
}