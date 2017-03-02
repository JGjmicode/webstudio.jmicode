<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['username', 'required', 'message' => 'Поле не должно быть пустым'],
            ['password', 'required', 'message' => "Поле не должно быть пустым"],
            ['username', 'validateStatus'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверно введен пользователь или пароль.');
            }
        }
    }

    public function validateStatus($attribute){
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validateStatus($this->username)) {
                $this->addError($attribute, 'Пользователь не активирован.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {        
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        return false;                
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }


}
