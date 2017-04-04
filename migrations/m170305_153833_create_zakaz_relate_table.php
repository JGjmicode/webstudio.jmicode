<?php

use yii\db\Migration;

/**
 * Handles the creation of table `zakaz_relate`.
 */
class m170305_153833_create_zakaz_relate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('zakaz_relate', [
            'id' => $this->primaryKey(),
            'zakaz_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),

        ],'DEFAULT CHARSET=utf8');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('zakaz_relate');
    }
}
