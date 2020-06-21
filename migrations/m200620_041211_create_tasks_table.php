<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m200620_041211_create_tasks_table extends Migration
{
    private $table_name = '{{%tasks}}';
    private $table_name_s = 'tasks';
    private $table_name_ref = '{{%ref_tasks_status}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull(),
            'description' => $this->text()->notNull(),
            'ref_tasks_status_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    
        $this->createIndex(
            'idx-' . $this->table_name_s . '-ref_tasks_status_id',
            $this->table_name,
            'ref_tasks_status_id'
        );
    
        $this->addForeignKey(
            'fk-' . $this->table_name_s . '-ref_tasks_status_id',
            $this->table_name,
            'ref_tasks_status_id',
            $this->table_name_ref,
            'id'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-' . $this->table_name_s . '-ref_tasks_status_id', $this->table_name);
    
        $this->dropIndex('idx-' . $this->table_name_s . '-ref_tasks_status_id', $this->table_name);
    
        $this->dropTable($this->table_name);
    }
}
