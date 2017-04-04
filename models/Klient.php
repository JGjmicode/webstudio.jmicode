<?php

namespace app\models;
use Yii;

class Klient extends \yii\db\ActiveRecord{
    
    public function getKontakt() {
        return $this->hasMany(Kontakt::className(), ["klient_id" => "id"]);
    }  
    
    public function getZakaz() {
        return $this->hasMany(Zakaz::className(), ["klient_id" => "id"]);
    }

    public function addClient(){
        $this->create_by = Yii::$app->user->getId();
        if($this->save()){
            return true;
        }
        return false;
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
