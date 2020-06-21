<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ref_tasks_status}}`.
 */
class m200620_035908_create_ref_tasks_status_table extends Migration
{
    private $table_name = '{{%ref_tasks_status}}';
    private $table_name_s = 'ref_tasks_status';//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'name' => $this->string(25)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    
        $data = [
            ['id' => 1, 'name' => 'Новое'],
            ['id' => 2, 'name' => 'Выполняется'],
            ['id' => 3, 'name' => 'Выполнено'],
        ];
    
        foreach ($data as $item) {
            $this->insert($this->table_name, [
                'id' => $item['id'],
                'name' => $item['name'],
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table_name);
    }
}
