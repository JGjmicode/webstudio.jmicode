<?php

use yii\db\Migration;

class m170228_092529_add_type_column_to_zakazfiles_table extends Migration
{
    public function up()
    {
        $this->addColumn('zakazfiles', 'type', $this->string(128));
    }

    public function down()
    {
        $this->dropColumn('zakazfiles', 'type');
    }
}
