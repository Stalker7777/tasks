<?php

namespace app\components;

use app\models\RefTasksStatus;
use app\models\Staff;
use app\models\Tasks;
use app\models\MerStaffTasks;

class TasksData
{
    /**
     * @param null $tasks_form
     * @return array
     */
    public function getData($tasks_form = null)
    {
        $where_str = '';
        $where_array = [];

        if($tasks_form !== null) {
            if(!empty($tasks_form->filter_name)) {
                $where_array[] = " name like '%" . $tasks_form->filter_name . "%' ";
            }
            if(!empty($tasks_form->filter_description)) {
                $where_array[] = " description like '%" . $tasks_form->filter_description . "%' ";
            }
            if(!empty($tasks_form->filter_status_id)) {
                $where_array[] = ' ref_tasks_status_id = ' . $tasks_form->filter_status_id . ' ';
            }
        }
    
        if(count($where_array) > 0) {
            $where_str = implode(' AND ', $where_array);
        }

        $model = Tasks::find()->where($where_str)->all();
        
        $result = [];
        
        foreach ($model as $item) {
            
            $mer_staff_tasks = MerStaffTasks::find()->where(['tasks_id' => $item['id']])->all();
            
            $staff_ids = [];
            $staff_name = [];
            
            foreach ($mer_staff_tasks as $mer) {
                $staff_ids[] = $mer->staff_id;
            }
    
            if(count($staff_ids) > 0) {
                $staff = Staff::find()->where(['id' => $staff_ids])->all();
                
                foreach ($staff as $item_staff) {
                    $staff_name[] = $item_staff->fio;
                }
            }
            
            $result[] = [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'status_name' => $this->getTaskName($item->ref_tasks_status_id),
                'staff_name' => implode(', ', $staff_name),
            ];
        }
        
        return ['error' => false, 'data' => $result];
    }
    
    /**
     * @param null $id
     * @return string
     */
    public function getTaskName($id = null) {
        
        if($id === null) return '';
        
        $tasks = RefTasksStatus::findOne(['id' => $id]);
        
        if($tasks !== null) {
            return $tasks->name;
        }
        
        return '';
    }
    
    /**
     * @param null $tasks
     * @return array
     */
    public function getDataEdit($tasks = null)
    {
        if ($tasks === null) return ['error' => true, 'message' => 'MODEL_EMPTY_DATA'];
        
        $model = Tasks::findOne(['id' => $tasks->id]);
        
        $staff = Staff::find()->all();
        
        $staff_attached = [];
    
        foreach ($staff as $staff_item) {
            $staff_attached[] = [
                'id' =>  $staff_item->id,
                'fio' => $staff_item->fio,
                'photo' => $staff_item->photo,
            ];
        }
    
        $checked_staff = [];
    
        $tasks_staff = MerStaffTasks::find()->where(['tasks_id' => $tasks->id])->all();
    
        foreach ($tasks_staff as $tasks_staff_item) {
            $checked_staff[] = $tasks_staff_item->staff_id;
        }
    
        if($model !== null) {
            return ['error' => false, 'data' => [
                'id' => $model->id,
                'name' => $model->name,
                'description' => $model->description,
                'ref_tasks_status_id' => $model->ref_tasks_status_id,
                'staff_attached' => $staff_attached,
                'checked_staff' => $checked_staff,
            ]];
        }
        else {
            return ['error' => false, 'data' => [
                'id' => '',
                'name' => '',
                'description' => '',
                'ref_tasks_status_id' => '',
                'staff_attached' => $staff_attached,
                'checked_staff' => [],
            ]];
        }
    }
    
    /**
     * @param null $tasks
     * @return array
     */
    public function setDataEdit($tasks = null)
    {
        if($tasks === null) return ['error' => true, 'message' => 'MODEL_EMPTY_DATA'];
        
        if(empty($tasks->id)) {
            $model = new Tasks();
            
            $model->id = $tasks->id;
            $model->name = $tasks->name;
            $model->description = $tasks->description;
            $model->ref_tasks_status_id = $tasks->ref_tasks_status_id;
            $model->created_at = time();
            $model->updated_at = time();
        }
        else {
            $model = Tasks::findOne(['id' => $tasks->id]);
            
            if($model === null) return ['error' => true, 'message' => 'MODEL_NOT_FIND'];
            
            $model->name = $tasks->name;
            $model->description = $tasks->description;
            $model->ref_tasks_status_id = $tasks->ref_tasks_status_id;
        }
        
        if($model->save()) {
    
            $this->setCheckedStaff($model->id, $tasks->checked_staff);
            
            return ['error' => false, 'save_id' => $model->id];
        }
        else {
            //var_dump($model->getErrors());
            return ['error' => true, 'message' => 'ERROR_SAVE_DATA'];
        }
    }
    
    /**
     * @param $id
     * @param $checked_staff
     */
    private function setCheckedStaff($id, $checked_staff)
    {
        MerStaffTasks::deleteAll(' tasks_id  = ' . $id);
        
        $checked_staff_array = explode(',', $checked_staff);
        
        foreach ($checked_staff_array as $item) {
            $mst = new MerStaffTasks();
    
            $mst->tasks_id = $id;
            $mst->staff_id = $item;
            
            $mst->save();
        }
    }
    
    
}