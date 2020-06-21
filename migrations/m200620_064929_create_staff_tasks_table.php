<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%staff_tasks}}`.
 */
class m200620_064929_create_staff_tasks_table extends Migration
{
    private $table_name = '{{%mer_staff_tasks}}';
    private $table_name_s = 'mer_staff_tasks';
    private $table_name_staff = '{{%staff}}';
    private $table_name_tasks = '{{%tasks}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_name, [
            'staff_id' => $this->integer()->notNull(),
            'tasks_id' => $this->integer()->notNull(),
        ]);
    
        $this->addPrimaryKey('pk-ids', $this->table_name, ['staff_id', 'tasks_id']);
        
        $this->createIndex(
            'idx-' . $this->table_name_s . '-staff_id',
            $this->table_name,
            'staff_id'
        );
        
        $this->addForeignKey(
            'fk-' . $this->table_name_s . '-staff_id',
            $this->table_name,
            'staff_id',
            $this->table_name_staff,
            'id'
        );
    
        $this->createIndex(
            'idx-' . $this->table_name_s . '-tasks_id',
            $this->table_name,
            'tasks_id'
        );
    
        $this->addForeignKey(
            'fk-' . $this->table_name_s . '-tasks_id',
            $this->table_name,
            'tasks_id',
            $this->table_name_tasks,
            'id'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-' . $this->table_name_s . '-tasks_id', $this->table_name);
    
        $this->dropIndex('idx-' . $this->table_name_s . '-tasks_id', $this->table_name);
    
        $this->dropForeignKey('fk-' . $this->table_name_s . '-staff_id', $this->table_name);
        
        $this->dropIndex('idx-' . $this->table_name_s . '-staff_id', $this->table_name);
        
        $this->dropTable($this->table_name);
    }}
