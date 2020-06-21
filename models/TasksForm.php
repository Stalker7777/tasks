<?php

namespace app\models;

use yii\base\Model;

class TasksForm extends model
{
    public $id;
    public $name;
    public $description;
    public $ref_tasks_status_id;
    public $checked_staff;
    
    public $filter_name;
    public $filter_description;
    public $filter_status_id;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'description', 'ref_tasks_status_id'], 'required'],
            [['description'], 'string'],
            [['id', 'ref_tasks_status_id'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['ref_tasks_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefTasksStatus::className(), 'targetAttribute' => ['ref_tasks_status_id' => 'id']],
            [['filter_name', 'filter_description'], 'string'],
            [['filter_status_id'], 'integer'],
            [['checked_staff'], 'string'],
        ];
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'description' => 'Описание',
            'ref_tasks_status_id' => 'Статус',
            'filter_name' => 'Наименование',
            'filter_description' => 'Описание',
            'filter_status_id' => 'Статус',
        ];
    }
    
}