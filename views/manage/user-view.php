<?php
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Html;
$this->title = 'Редактирование  пользователя';
//var_export($model->userRoles, false);
?>
<h3 class="text-center">Редактирование  пользователя <?=$user->name?></h3>

<div class="col-md-6">
    <h4 class="text-center">Данные пользователя</h4>
    <?php $userEdit = ActiveForm::begin(); ?>
    <div class='user-account'>
        <?=$userEdit->field($user, 'name')?>
        <?=$userEdit->field($user, 'skype')?>
        <?=$userEdit->field($user, 'phone')?>
        <?=$userEdit->field($user, 'e_mail')?>
        <?=$userEdit->field($user, 'reset_user_password')->checkbox(['id' => 'checkbox-reset-pass', 'value' => 0])?>
        <?=$userEdit->field($user, 'reset_password')->passwordInput()?>
        <?=$userEdit->field($user, 'reset_password_repeat')->passwordInput()?>




        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="col-md-6">
    <div class="user-roles">
        <h4 class="text-center">Роли пользователя</h4>
        <?php
        foreach($model->userRoles as $role){?>
            <div class="col-md-10">
                <?=DetailView::widget([
                    'model' => $role,
                    'attributes' => [
                        ['label' => 'Название',
                         'value' => $role->name
                        ],
                        ['label' => 'Описание',
                            'value' => $role->description
                        ],


                    ],
                ])?>
            </div>
            <div class="col-md-2">
                <?=Html::a('удалить',['/manage/del-role', 'role' => $role->name, 'id' => $user->id], ["class" => "btn btn-danger btn-xs"])?>
            </div>
        <?php }?>
    </div>
    <div class="available-roles">
        <h4 class="text-center">Доступные роли</h4>
        <?php if(empty($model->roles)){
            echo '<h4 class="text-center">Доступные роли отстутствуют</h4>';
        }else{ ?>
            <table class="table table-striped table-roles">
                <tr>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>добавить</th>
                </tr>
                <?php foreach($model->roles as $role){?>
                    <tr>
                        <td><?=$role->name?></td>
                        <td><?=$role->description?></td>
                        <td><?=Html::a('Добавить',['/manage/add-role', 'role' => $role->name, 'id' => $user->id], ["class" => "btn btn-primary btn-xs"])?></td>
                    </tr>
                <?php }?>
            </table>
        <?php }?>
    </div>
</div>
<div class="clearfix"></div>
<div class="col-md-6">
    <h4 class="text-center">Че нить еще</h4>
</div>

<?php
$js = '$("#checkbox-reset-pass").click(function(){
if($("#checkbox-reset-pass").val() == "1"){
    $("#checkbox-reset-pass").val(0); 
}else{
    $("#checkbox-reset-pass").val(1);  
}});';
$this->registerJs($js);
?>
