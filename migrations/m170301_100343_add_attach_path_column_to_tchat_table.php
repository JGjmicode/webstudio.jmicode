<?php

use yii\db\Migration;

/**
 * Handles adding attach_path to table `tchat`.
 */
class m170301_100343_add_attach_path_column_to_tchat_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('tchat', 'attach_path', $this->string(128));
        $this->addColumn('tchat', 'attach_name', $this->string(128));
        $this->addColumn('tchat', 'attach_ext', $this->string(10));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('tchat', 'attach_path');
        $this->dropColumn('tchat', 'attach_name');
        $this->dropColumn('tchat', 'attach_ext');
    }
}
