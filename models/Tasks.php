<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $ref_tasks_status_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property MerStaffTasks[] $merStaffTasks
 * @property RefTasksStatus $refTasksStatus
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'ref_tasks_status_id', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['ref_tasks_status_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['ref_tasks_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefTasksStatus::className(), 'targetAttribute' => ['ref_tasks_status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'ref_tasks_status_id' => 'Ref Tasks Status ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[MerStaffTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerStaffTasks()
    {
        return $this->hasMany(MerStaffTasks::className(), ['tasks_id' => 'id']);
    }

    /**
     * Gets query for [[RefTasksStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefTasksStatus()
    {
        return $this->hasOne(RefTasksStatus::className(), ['id' => 'ref_tasks_status_id']);
    }
}
