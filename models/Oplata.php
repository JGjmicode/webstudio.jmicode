<?php
namespace app\models;

class Oplata extends \yii\db\ActiveRecord{

    public function rules()
    {
        return [
            [['oplata', 'date_opl'], 'required'],
            [['prim', 'zakaz_id'], 'default']
        ];
    }

    public function attributeLabels()
    {
        return [
            'oplata' => 'Сумма',
            'date_opl' => 'Дата',
            'prim' => 'Примечание',
        ];
    }

}

