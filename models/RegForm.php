<?php

namespace app\models;

use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegForm extends Model
{
    public $name;
    public $password;
    public $password_repeat;
    public $e_mail;
    
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['e_mail'], 'required'],
            [['e_mail'], 'email'],
            ['name', 'required', 'message' => 'Поле не должно быть пустым'],
            ['password_repeat', 'required', 'message' => 'Поле не должно быть пустым'],
            ['password', 'compare', 'message' => 'Введенные пароли не совпадают'],
            // rememberMe must be a boolean value

        ];
    }
    
    public function reg()
    {
        $user = new User();
        $user->name = $this->name;
        $user->e_mail = $this->e_mail;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save(false) ? $user : null;
    }

}