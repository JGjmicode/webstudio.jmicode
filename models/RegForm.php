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
    
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
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
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save(false) ? $user : null;
    }

}