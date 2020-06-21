<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property int $id
 * @property string $fio
 * @property string $photo
 * @property int $created_at
 * @property int $updated_at
 *
 * @property MerStaffTasks[] $merStaffTasks
 */
class Staff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'photo', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['fio', 'photo'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'photo' => 'Photo',
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
        return $this->hasMany(MerStaffTasks::className(), ['staff_id' => 'id']);
    }
}
