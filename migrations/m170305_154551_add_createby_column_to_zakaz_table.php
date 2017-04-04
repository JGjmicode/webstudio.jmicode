<?php

use yii\db\Migration;

/**
 * Handles adding createby to table `zakaz`.
 */
class m170305_154551_add_createby_column_to_zakaz_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('zakaz', 'create_by', $this->integer()->notNull()->defaultValue(1));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('zakaz', 'create_by');
    }
}
