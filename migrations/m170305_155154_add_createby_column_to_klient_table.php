<?php

use yii\db\Migration;

/**
 * Handles adding createby to table `klient`.
 */
class m170305_155154_add_createby_column_to_klient_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('klient', 'create_by', $this->integer()->notNull()->defaultValue(1));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('klient', 'create_by');
    }
}
