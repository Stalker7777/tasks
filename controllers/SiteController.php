<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\components\StaffData;
use app\components\TasksData;
use app\models\StaffForm;
use app\models\TasksForm;
use app\models\RefTasksStatus;

class SiteController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model_staff = new StaffForm();
        $model_tasks = new TasksForm();
    
        $ref_tasks_status = RefTasksStatus::find()->all();
        $items_tasks = ArrayHelper::map($ref_tasks_status, 'id', 'name');
    
        return $this->render('index', [
            'model_staff' => $model_staff,
            'model_tasks' => $model_tasks,
            'items_tasks' => $items_tasks,
        ]);
    }
    
    /**
     * @return string
     */
    public function actionGetStaffData()
    {
        $staff_data = new StaffData();
        $staff_form = new StaffForm();
    
        $staff_form->load(Yii::$app->request->post());
        
        $result = $staff_data->getData($staff_form);
        
        if(!$result['error']) {
            return json_encode($result);
        }
        
        return json_encode(['error' => true, 'message' => 'ERROR_NOT_GET_DATA']);
    }
    
    /**
     * @return string
     */
    public function actionGetStaffDataEdit()
    {
        $staff_form = new StaffForm();
        $staff_data = new StaffData();
        
        if($staff_form->load(Yii::$app->request->post())) {
            
            $result = $staff_data->getDataEdit($staff_form);
            
            return json_encode($result);
        }
        
        return json_encode(['error' => true, 'message' => 'ERROR_SAVE_DATA_EDIT']);
    }
    
    /**
     * @return string
     */
    public function actionSetStaffDataEdit()
    {
        $staff_form = new StaffForm();
        $staff_data = new StaffData();
    
        if($staff_form->load(Yii::$app->request->post())) {
    
            $result = $staff_data->setDataEdit($staff_form);
            
            return json_encode($result);
        }
    
        return json_encode(['error' => true, 'message' => 'ERROR_SAVE_DATA_EDIT']);
    }
    
    /**
     * @return string
     */
    public function actionGetTasksData()
    {
        $tasks_data = new TasksData();
        $tasks_form = new TasksForm();
    
        $tasks_form->load(Yii::$app->request->post());
        
        $result = $tasks_data->getData($tasks_form);
        
        if(!$result['error']) {
            return json_encode($result);
        }
        
        return json_encode(['error' => true, 'message' => 'ERROR_NOT_GET_DATA']);
    }
    
    /**
     * @return string
     */
    public function actionGetTasksDataEdit()
    {
        $tasks_form = new TasksForm();
        $tasks_data = new TasksData();
        
        if($tasks_form->load(Yii::$app->request->post())) {
        
            $result = $tasks_data->getDataEdit($tasks_form);
            
            return json_encode($result);
        }
        
        return json_encode(['error' => true, 'message' => 'ERROR_SAVE_DATA_EDIT']);
    }
    
    /**
     * @return string
     */
    public function actionSetTasksDataEdit()
    {
        $tasks_form = new TasksForm();
        $tasks_data = new TasksData();
        
        if($tasks_form->load(Yii::$app->request->post())) {
            
            $result = $tasks_data->setDataEdit($tasks_form);
            
            return json_encode($result);
        }
        
        return json_encode(['error' => true, 'message' => 'ERROR_SAVE_DATA_EDIT']);
    }
}
