<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mer_staff_tasks".
 *
 * @property int $staff_id
 * @property int $tasks_id
 *
 * @property Staff $staff
 * @property Tasks $tasks
 */
class MerStaffTasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mer_staff_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'tasks_id'], 'required'],
            [['staff_id', 'tasks_id'], 'integer'],
            [['staff_id', 'tasks_id'], 'unique', 'targetAttribute' => ['staff_id', 'tasks_id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::className(), 'targetAttribute' => ['staff_id' => 'id']],
            [['tasks_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['tasks_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => 'Staff ID',
            'tasks_id' => 'Tasks ID',
        ];
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'staff_id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'tasks_id']);
    }
}
