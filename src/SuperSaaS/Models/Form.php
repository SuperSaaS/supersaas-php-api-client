<?php namespace SuperSaaS\Models;

class Form extends BaseModel
{
    public $content;
    public $createdOn;
    public $deleted;
    public $id;
    public $reservationProcessId;
    public $superFormId;
    public $uniq;
    public $updatedName;
    public $updatedOn;
    public $userId;

    public function __construct ($attributes=array()) {
        $this->content = $attributes['content'];
        $this->createdOn = $attributes['created_on'];
        $this->deleted = $attributes['deleted'];
        $this->id = $attributes['id'];
        $this->reservationProcessId = $attributes['reservation_process_id'];
        $this->superFormId = $attributes['super_form_id'];
        $this->uniq = $attributes['uniq'];
        $this->updatedName = $attributes['updated_name'];
        $this->updatedOn = $attributes['updated_on'];
        $this->userId = $attributes['user_id'];

        $this->errors = $attributes['errors'];
    }
}