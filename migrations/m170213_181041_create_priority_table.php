<?php

use yii\db\Migration;

/**
 * Handles the creation of table `priority`.
 */
class m170213_181041_create_priority_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('priority', [
            'id' => $this->primaryKey(),
            'priority' => $this->string(20)->notNull(),
            'class' => $this->string(20),
        ], 'DEFAULT CHARSET=utf8');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('priority');
    }
}
