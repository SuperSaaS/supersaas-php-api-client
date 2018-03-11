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
        $this->price = $attributes['price'];
        $this->resName = $attributes['res_name'];
        $this->resourceId = $attributes['resource_id'];
        $this->role = $attributes['role'];
        $this->scheduleId = $attributes['schedule_id'];
        $this->scheduleName = $attributes['schedule_name'];
        $this->serviceId = $attributes['service_id'];
        $this->serviceName = $attributes['service_name'];
        $this->slotId = $attributes['slot_id'];
        $this->start = $attributes['start'];
        $this->status = $attributes['status'];
        $this->superField = $attributes['super_field'];
        $this->updatedBy = $attributes['updated_by'];
        $this->updatedOn = $attributes['updated_on'];
        $this->userId = $attributes['user_id'];
        $this->waitlisted = $attributes['waitlisted'];

        $this->errors = $attributes['errors'];

        if (!empty($attributes['form'])) {
            $this->form = new Form($attributes['form']);
        }

        if (!empty($attributes['slot'])) {
            $this->slot = new Slot($attributes['slot']);
        }
    }
}