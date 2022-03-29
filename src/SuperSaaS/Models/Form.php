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
        $this->content = $this->issetAttr($attributes, 'content');
        $this->createdOn = $this->issetAttr($attributes, 'created_on');
        $this->deleted = $this->issetAttr($attributes, 'deleted');
        $this->id = $this->issetAttr($attributes, 'id');
        $this->reservationProcessId = $this->issetAttr($attributes, 'reservation_process_id');
        $this->superFormId = $this->issetAttr($attributes, 'super_form_id');
        $this->uniq = $this->issetAttr($attributes, 'uniq');
        $this->updatedName = $this->issetAttr($attributes, 'updated_name');
        $this->updatedOn = $this->issetAttr($attributes, 'updated_on');
        $this->userId = $this->issetAttr($attributes, 'user_id');

        $this->errors = $this->issetAttr($attributes, 'errors');
    }
}