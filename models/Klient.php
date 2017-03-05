<?php

namespace app\models;

class Klient extends \yii\db\ActiveRecord{
    
    public function getKontakt() {
        return $this->hasMany(Kontakt::className(), ["klient_id" => "id"]);
    }  
    
    public function getZakaz() {
        return $this->hasMany(Zakaz::className(), ["klient_id" => "id"]);
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['prim'], 'default'],
        ];
    }

    public function attributeLabels() {
        return array(
            "name" => "Имя",
            "prim" => "Примечание",
        );
    }    
}
