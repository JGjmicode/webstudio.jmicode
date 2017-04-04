<?php

use yii\db\Migration;

/**
 * Handles adding updateby to table `users`.
 */
class m170306_151234_add_updateby_column_to_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('users', 'update_by', $this->integer());
        $this->addColumn('users', 'update_at', $this->timestamp()->defaultValue(NULL));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('users', 'update_by');
        $this->dropColumn('users', 'update_at');
    }
}
