<?php

use yii\db\Migration;

/**
 * Class m200620_070846_insert_test_data_to_tables
 */
class m200620_070846_insert_test_data_to_tables extends Migration
{
    private $table_name_staff = '{{%staff}}';
    private $table_name_tasks = '{{%tasks}}';
    private $table_name_mer = '{{%mer_staff_tasks}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $staff_data = [];
        $tasks_data = [];
        
        $ids = range(1, 10, 1);
        
        foreach ($ids as $id) {
            $staff_data[] = ['id' => $id, 'fio' => 'Name Name Name ' . $id];
            $tasks_data[] = ['id' => $id, 'name' => 'Task name ' . $id, 'description' => 'Description description description description description ' . $id];
        }
        
        $mer_data = [
            ['staff_id' => 1, 'tasks_id' => 1],
            ['staff_id' => 1, 'tasks_id' => 2],
            ['staff_id' => 1, 'tasks_id' => 3],
            ['staff_id' => 2, 'tasks_id' => 4],
            ['staff_id' => 2, 'tasks_id' => 5],
            ['staff_id' => 2, 'tasks_id' => 6],
            ['staff_id' => 5, 'tasks_id' => 8],
            ['staff_id' => 5, 'tasks_id' => 9],
            ['staff_id' => 5, 'tasks_id' => 10],
            ['staff_id' => 8, 'tasks_id' => 4],
            ['staff_id' => 8, 'tasks_id' => 6],
            ['staff_id' => 8, 'tasks_id' => 9],
        ];
    
        $this->delete($this->table_name_staff);
        $this->delete($this->table_name_tasks);
        $this->delete($this->table_name_mer);
        
        foreach ($staff_data as $item) {
            $this->insert($this->table_name_staff, [
                'id' => $item['id'],
                'fio' => $item['fio'],
                'photo' => '/tasks/web/photos/empty/staff.png',
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    
        foreach ($tasks_data as $item) {
            $this->insert($this->table_name_tasks, [
                'id' => $item['id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'ref_tasks_status_id' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    
        foreach ($mer_data as $item) {
            $this->insert($this->table_name_mer, [
                'staff_id' => $item['staff_id'],
                'tasks_id' => $item['tasks_id'],
            ]);
        }
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete($this->table_name_mer);
        $this->delete($this->table_name_staff);
        $this->delete($this->table_name_tasks);
    }
}
