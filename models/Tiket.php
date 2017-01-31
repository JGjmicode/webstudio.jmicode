<?php

namespace app\models;

class Tiket extends \yii\db\ActiveRecord{
    
    public function getTchat() {
        return $this->hasMany(Tchat::className(), ["tiket_id"=>"id"]);
    }
    
    public function getUsers() {
        return $this->hasOne(Users::className(), ["id"=>"user_id"]);
    }
            
    public function setTiketText($text, $id){
        $userId = \Yii::$app->user->getId();
        \Yii::$app->db->createCommand()->insert(
                'tchat',
                [
                   'tiket_id' => $id,
                    'user_id' => $userId,
                    'date_add' => date("Y-m-d"),
                    'txt' => $text,
                ]
                )->execute();
    }
    
   function getTiket($id) {
        return Tiket::find()->joinWith("users")->joinWith("tchat")->where("tiket.id=".$id)->all();
    }  
    
   
}