<?php

namespace app\models;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName() {
        return 'users';
    }
    
    public static function findByUserName($username) {
        return static::findOne([
            "name" => $username
        ]);
    }
    
    public function setPassword($password) {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }
    
    public function generateAuthKey() {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }
    
    public function validatePassword($password) {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
    
    /*  Аутентификация */
    
  public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    } 
    public function getAvatar($id) {
        return $this->avatar;
    }

        public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }    
    
    
}
