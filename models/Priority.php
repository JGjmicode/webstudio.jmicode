<?php

namespace app\models;

use yii\db\ActiveRecord;

class Priority extends ActiveRecord{

    public function rules()
    {
        return [
            [['priority'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'priority' => 'Приоритет'
        ];
    }

}