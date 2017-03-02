<?php

use yii\db\Migration;

/**
 * Handles adding profile_ to table `users`.
 */
class m170301_154306_add_profile__column_to_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('users', 'skype', $this->string(128));
        $this->addColumn('users', 'phone', $this->string(128));
        $this->addColumn('users', 'e_mail', $this->string(128));
        $this->addColumn('users', 'role', $this->string(128));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('users', 'skype');
        $this->dropColumn('users', 'phone');
        $this->dropColumn('users', 'e_mail');
        $this->dropColumn('users', 'role');
    }
}
