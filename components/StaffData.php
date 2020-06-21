<?php

namespace app\components;

use Yii;
use app\models\Staff;
use app\models\Tasks;
use app\models\MerStaffTasks;

class StaffData
{
    /**
     * @param null $staff_form
     * @return array
     */
    public function getData($staff_form = null)
    {
        $where_fio = '';
        $where_tasks = '';
        
        if($staff_form !== null) {
            if(!empty($staff_form->filter_fio)) {
                $where_fio = " fio like '%" . $staff_form->filter_fio . "%' ";
            }
            if(!empty($staff_form->filter_tasks)) {
                $where_tasks = " AND tasks.name like '%" . $staff_form->filter_tasks . "%' ";
            }
        }
    
        if(empty($staff_form->filter_tasks)) {
            $model = Staff::find()->where($where_fio)->all();
        }
        else {
            $model = Staff::find()
                ->innerJoin('mer_staff_tasks', 'staff.id = mer_staff_tasks.staff_id')
                ->innerJoin('tasks', "tasks.id = mer_staff_tasks.tasks_id " . $where_tasks)
                ->where($where_fio)
                ->all();
        }
        
        $result = [];
        
        foreach ($model as $item) {
            
            $mer_staff_tasks = MerStaffTasks::find()->where(['staff_id' => $item['id']])->all();
            
            $tasks_ids = [];
            $tasks_name = [];
            
            foreach ($mer_staff_tasks as $mer) {
                $tasks_ids[] = $mer->tasks_id;
            }
            
            if (count($tasks_ids) > 0) {
                $tasks = Tasks::find()->where(['id' => $tasks_ids])->all();
                
                foreach ($tasks as $task) {
                    $tasks_name[] = $task->name;
                }
            }
            
            $result[] = [
                'id'         => $item->id,
                'fio'        => $item->fio,
                'photo'      => $item->photo,
                'tasks_name' => implode(', ', $tasks_name),
                'where_tasks'=> $where_tasks,
            ];
        }
        
        return ['error' => false, 'data' => $result];
    }
    
    /**
     * @param null $staff
     * @return array
     */
    public function getDataEdit($staff = null)
    {
        if ($staff === null) return ['error' => true, 'message' => 'MODEL_EMPTY_DATA'];
    
        $model = Staff::findOne(['id' => $staff->id]);
        
        $tasks = Tasks::find()->all();
        
        $tasks_attached = [];
        
        foreach ($tasks as $task_item) {
            $tasks_attached[] = [
                'id' =>  $task_item->id,
                'name' => $task_item->name,
                'description' => $task_item->description,
            ];
        }
        
        $checked_tasks = [];
    
        $tasks_staff = MerStaffTasks::find()->where(['staff_id' => $staff->id])->all();
        
        foreach ($tasks_staff as $tasks_staff_item) {
            $checked_tasks[] = $tasks_staff_item->tasks_id;
        }
        
        if($model !== null) {
            return ['error' => false, 'data' => [
                'id' => $model->id,
                'fio' => $model->fio,
                'photo' => $model->photo,
                'tasks_attached' => $tasks_attached,
                'checked_tasks' => $checked_tasks,
            ]];
        }
        else {
            return ['error' => false, 'data' => [
                'id' => '',
                'fio' => '',
                'photo' => '',
                'tasks_attached' => $tasks_attached,
                'checked_tasks' => [],
            ]];
        }
    }
    
    /**
     * @param null $staff
     * @return array
     */
    public function setDataEdit($staff = null)
    {
        if($staff === null) return ['error' => true, 'message' => 'MODEL_EMPTY_DATA'];

        if(empty($staff->photo)) {
            $photo = Yii::getAlias('@web/photos/empty/staff.png');
        }
        else {
            $photo = $staff->photo;
        }
    
        if(empty($staff->id)) {
            $model = new Staff();
    
            $model->id = $staff->id;
            $model->fio = $staff->fio;
            $model->photo = $photo;
            $model->created_at = time();
            $model->updated_at = time();
        }
        else {
            $model = Staff::findOne(['id' => $staff->id]);
            
            if($model === null) return ['error' => true, 'message' => 'MODEL_NOT_FIND'];
    
            $model->fio = $staff->fio;
            $model->photo = $photo;
        }
    
        if($model->save()) {
            
            $this->setCheckedTasks($model->id, $staff->checked_tasks);
            
            return ['error' => false, 'save_id' => $model->id];
        }
        else {
            //var_dump($model->getErrors());
            return ['error' => true, 'message' => 'ERROR_SAVE_DATA'];
        }
    }
    
    /**
     * @param $id
     * @param $checked_tasks
     */
    private function setCheckedTasks($id, $checked_tasks)
    {
        MerStaffTasks::deleteAll(' staff_id  = ' . $id);
        
        $checked_tasks_array = explode(',', $checked_tasks);
        
        foreach ($checked_tasks_array as $item) {
            $mst = new MerStaffTasks();
    
            $mst->staff_id = $id;
            $mst->tasks_id = $item;
    
            $mst->save();
        }
    }
}