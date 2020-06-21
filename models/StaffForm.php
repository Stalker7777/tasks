<?php

namespace app\models;

use yii\base\Model;

class StaffForm extends model
{
    public $id;
    public $fio;
    public $photo;
    public $checked_tasks;
    
    public $filter_fio;
    public $filter_tasks;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['fio', 'photo'], 'required'],
            [['fio', 'photo'], 'string', 'max' => 500],
            [['filter_fio', 'filter_tasks'], 'string', 'max' => 500],
            [['id'], 'integer'],
            [['checked_tasks'], 'string'],
        ];
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'photo' => 'Фото',
            'filter_fio' => 'ФИО',
            'filter_tasks' => 'Задания',
        ];
    }
    
}