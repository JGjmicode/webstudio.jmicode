<?php

use yii\db\Migration;

class m170214_094035_add_column_to_tiket_table extends Migration
{
    public function up()
    {
        $this->addColumn('tiket', 'priority_id', $this->integer());
        $this->addColumn('tiket', 'performer_id', $this->integer());
        $this->addColumn('tiket', 'dead_line', $this->date());
        $this->addColumn('tiket', 'status', $this->boolean()->defaultValue(true));
    }

    public function down()
    {
        $this->dropColumn('tiket', 'priority_id');
        $this->dropColumn('tiket', 'performer_id');
        $this->dropColumn('tiket', 'dead_line');
        $this->dropColumn('tiket', 'status');

        }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
