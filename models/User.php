<?php

namespace app\models;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use Yii;
use kartik\alert\Alert;
class User extends ActiveRecord implements IdentityInterface{

    public $new_password;
    public $new_password_repeat;
    public $old_password;

    const ACTIVE = 1;


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

    public function validateStatus($name){
        $user = self::findOne(['name' => $name]);
        if($user->status == self::ACTIVE){
            return true;
        }else{
            return false;
        }
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

    public function getName()
    {
        return $this->name;
    }

    public function editProfile(){
        if($this->new_password != '') {
            if ($this->validatePassword($this->old_password)) {
                $this->setPassword($this->new_password);
            }
        }
        $pathArray = explode('/', $this->avatar);
        $fileName = array_pop($pathArray);
        $this->avatar = 'img/avatar/'.$fileName;
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }

    public function rules()
    {
        return [
            [['new_password_repeat', 'name', 'skype', 'e_mail', 'phone', 'avatar'], 'default'],
            ['new_password', 'compare', 'message' => 'Введенные пароли не совпадают'],
            [['e_mail'], 'email'],
            ['old_password', 'required', 'when' => function ($model) {
                return $model->new_password != '';
                }, 'whenClient' => "function (attribute, value) {
                return $('#user-new_password').val() != '';
                }"],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Login',
            'e_mail' => 'E-Mail',
            'phone' => 'Телефон',
            'avatar' => 'Аватарка',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Новый пароль еще раз',
            'old_password' => 'Старый пароль'
        ];

    }

    public static function setLastLogin(){
        $user = self::findOne(Yii::$app->user->getId());
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
    }

    public static function activateUser($id){
        $user = self::findOne($id);
        if(!is_null($user)){
            $user->status = true;
            if($user->save()){
                return true;
            }else{
                return false;
            }
        }else{
            Yii::$app->session->addFlash(\kartik\alert\Alert::TYPE_DANGER, 'Пользователь не найден');
            return false;
        }
    }

    public static function deactivateUser($id){
        $user = self::findOne($id);
        if(!is_null($user)){
            $user->status = false;
            if($user->save()){
                return true;
            }else{
                return false;
            }
        }else{
            Yii::$app->session->addFlash(\kartik\alert\Alert::TYPE_DANGER, 'Пользователь не найден');
            return false;
        }
    }

}
