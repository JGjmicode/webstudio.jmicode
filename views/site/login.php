<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';

?>
<div class="site-login">
    
    <div class="container">
    <p>Введите регистрационные данные:</p>
    <div class="login-block">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логин') ?>
        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>


    </div>
    
        <div class="form-group">            
            <div class="col-lg-offset-4 col-lg-11">                
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    <?= Html::a("Регистрация", yii\helpers\Url::toRoute("register"), ['class' => 'register-btn']); ?>
</div>
</div>
