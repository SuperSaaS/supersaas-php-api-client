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

    public function __construct($attributes=array())
    {
        $this->address = $this->issetAttr($attributes, 'address');
        $this->country = $this->issetAttr($attributes, 'country');
        $this->createdBy = $this->issetAttr($attributes, 'created_by');
        $this->description = $this->issetAttr($attributes, 'description');
        $this->createdOn = $this->issetAttr($attributes, 'created_on');
        $this->deleted = $this->issetAttr($attributes, 'deleted');
        $this->email = $this->issetAttr($attributes, 'email');
        $this->field1 = $this->issetAttr($attributes, 'field_1');
        $this->field2 = $this->issetAttr($attributes, 'field_2');
        $this->field1R = $this->issetAttr($attributes, 'field_1_r');
        $this->field2R = $this->issetAttr($attributes, 'field_2_r');
        $this->finish = $this->issetAttr($attributes, 'finish');
        $this->formId = $this->issetAttr($attributes, 'form_id');
        $this->fullName = $this->issetAttr($attributes, 'full_name');
        $this->id = $this->issetAttr($attributes, 'id');
        $this->mobile = $this->issetAttr($attributes, 'mobile');
        $this->name = $this->issetAttr($attributes, 'name');
        $this->phone = $this->issetAttr($attributes, 'phone');
        $this->price = $this->issetAttr($attributes, 'price');
        $this->resName = $this->issetAttr($attributes, 'res_name');
        $this->resourceId = $this->issetAttr($attributes, 'resource_id');
        $this->scheduleId = $this->issetAttr($attributes, 'schedule_id');
        $this->scheduleName = $this->issetAttr($attributes, 'schedule_name');
        $this->serviceId = $this->issetAttr($attributes, 'service_id');
        $this->serviceName = $this->issetAttr($attributes, 'service_name');
        $this->slotId = $this->issetAttr($attributes, 'slot_id');
        $this->start = $this->issetAttr($attributes, 'start');
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
