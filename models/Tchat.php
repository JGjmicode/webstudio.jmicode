<?php

namespace app\models;


class Tchat extends \yii\db\ActiveRecord{
    
    public function getUsers() {
        return $this->hasOne(Users::className(), ["id"=>"user_id"]);
    }    

    function getTchat () {
        return Tiket::find()->joinWith("users")->all();
    }
}