<?php
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование профиля';

?>

<div class="container">
    <h4> Редактирование профиля </h4>
    <?php
        $avatarFiles = FileHelper::findFiles('img/avatar/', ['only' =>['*.png', '*.gif']]);
    ?>
    <?php $userEdit = ActiveForm::begin(); ?>
    <div class='avatar'>
        <img class='user-avatar' name='avatar' src='/<?= $user->avatar ?>' width='64' height='64'></img>

    </div>
    <div class='user-account'>
        <?=$userEdit->field($user, 'name')?>
        <?=$userEdit->field($user, 'skype')?>
        <?=$userEdit->field($user, 'phone')?>
        <?=$userEdit->field($user, 'e_mail')?>
        <?=$userEdit->field($user, 'change_password')->checkbox(['id' => 'checkbox-change-pass', 'value' => 0])?>
        <?=$userEdit->field($user, 'old_password')->passwordInput()?>
        <?=$userEdit->field($user, 'new_password')->passwordInput()?>
        <?=$userEdit->field($user, 'new_password_repeat')->passwordInput()?>
        <?=$userEdit->field($user, 'avatar')->hiddenInput(['class' => 'avatar-field'])->label(false)?>


        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class='avatar-pictures'>
        <p>Select avatar:
        <br>
        <?php
            foreach ($avatarFiles as $file) {
                if ($file == "img/avatar/no_avatar.png") continue;
                //if ($file == $userIdentity['avatar'])                    
                echo "<img class='avatar-image' src='/".$file."' width=64 geight=64></img>";
            }
        ?>
    </div>
</div>

<script>
    var images = document.querySelectorAll('.avatar-image');
    var userImage = document.querySelector('.user-avatar');
    var selectedImage;
    
    for (var key in images) { 
        if (userImage.src == images[key].src) {
            images[key].classList.add('avatar-image--active');
            selectedImage = images[key];
            }
        images[key].addEventListener('click', function(evt){
           setUserImage(evt);
        });
    }
    function setUserImage(evt){    
        if (selectedImage)
            selectedImage.classList.remove('avatar-image--active');
        userImage.src = evt.target.src;           
        evt.target.classList.add('avatar-image--active');
        selectedImage = evt.target;
        var avField = document.querySelector('.avatar-field');
        console.log(userImage.src);
        avField.value = evt.target.src;
    }
    
</script>

<?php
    $js = '$("#checkbox-change-pass").click(function(){
if($("#checkbox-change-pass").val() == "1"){
    $("#checkbox-change-pass").val(0); 
}else{
    $("#checkbox-change-pass").val(1);  
}});';
    $this->registerJs($js);
?>