<?php
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\widgets\ActiveForm;
?>

<div class="container">
    <h4> Редактирование профиля </h4>
    <?php
        $userIdentity = Yii::$app->user->identity;                
        $avatarFiles = FileHelper::findFiles('img/avatar/', ['only' =>['*.png', '*.gif']]);
    ?>
    <?php ActiveForm::begin(); ?>
    <div calss='avatar'>
        <img class='user-avatar' name='avatar' src='/<?= $userIdentity['avatar'] ?>' width='64' height='64'></img>
        <?= Html::input('text', 'avatar-field', $userIdentity['avatar'], ['class' => 'avatar-field']); ?>
    </div>
    <div class='user-account'>
        <?= Html::input('text', 'login', $userIdentity['name']); ?><br>
        <?= Html::input('password', 'pass'); ?><br>
        <?= Html::input('password', 're-pass'); ?><br>
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
        avField.value = evt.target.src;
    }
    
</script>

