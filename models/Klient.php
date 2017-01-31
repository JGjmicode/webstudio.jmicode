<?php

namespace app\models;

class Klient extends \yii\db\ActiveRecord{
    
    public function getKontakt() {
        return $this->hasMany(Kontakt::className(), ["klient_id" => "id"]);
    }  
    
    public function getZakaz() {
        return $this->hasMany(Zakaz::className(), ["klient_id" => "id"]);
    }
    
    public function setContact($name, $phone, $mail, $skype, $prim, $id){
        \Yii::$app->db->createCommand()->insert(
                'kontakt',
                [
                   'klient_id' => $id,
                   'name' => $name,
                   'phone' => $phone,
                   'email' => $mail,
                   'skype' => $skype,                    
                   'prim' => $prim,
                ]
                )->execute();             
    }
    
    public function setNewKlient($params) {
        \Yii::$app->db->createCommand()->insert(
                'klient', 
                [
                    'name' => $params['name'],
                    'prim' => $params['prim'],
                ])->execute();
        $id = \Yii::$app->db->getLastInsertID();
        return $id;
    }    
            
    function getKlient($id) {
        return Klient::find()->joinWith("kontakt")->joinWith("zakaz")->where("klient.id=".$id)->all();
    }    
    

    public function attributeLabels() {
        return array(
            "id" => "id",
            "name" => "name",
            "prim" => "prim",
        );
    }    
}
