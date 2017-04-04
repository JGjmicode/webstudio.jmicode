<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация нового пользователя';

?>
<div class="container">   

    <p>Регистрация нового пользователя:</p>
    <div class="login-block">
    <?php $form = ActiveForm::begin([
        'id' => 'reg-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
               
    
        <?= $form->field($model, 'name')->textInput()->label('Логин') ?>

        <?= $form->field($model, 'password_repeat')->passwordInput()->label('Пароль') ?>
    
        <?= $form->field($model, 'password')->passwordInput()->label('Пароль еще раз') ?>

        <?= $form->field($model, 'e_mail')->passwordInput()->label('E-Mail') ?>

    </div>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-11">
                <?= Html::submitButton('Зарегистрировать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

  
</div>