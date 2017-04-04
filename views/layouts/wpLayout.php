<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use kartik\alert\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <header class="header">      
        <div class="container">
          <div class="main-logo">
                <?= Html::img("/img/sitelogo.png"); ?>
          </div>
            <!--Отображение меню для зарегистрированных пользователей-->
            <?php
                if(!Yii::$app->user->isGuest){
            ?>
          <nav class="main-nav">
            <ul>
                <li><?= Html::a("Проекты", yii\helpers\Url::toRoute("projects/index")); ?></li>
                <li><?= Html::a("Клиенты", yii\helpers\Url::toRoute("client/index")); ?></li>
                <li><?= Html::a("Задачи", yii\helpers\Url::toRoute(["tikets/index", 'TiketSearch[active]' => true])); ?></li>
                <li><?= Html::a("Профиль", yii\helpers\Url::toRoute("site/userprofile")); ?></li>
                <li><?= Html::a("Профили пользователей", yii\helpers\Url::toRoute("manage/manage-profile")); ?></li>
            </ul>
          </nav>
            
            <?php
                }
            ?>
            <!--отображение меню-->
            <div class="login">
                <?php
                if (Yii::$app->user->isGuest) {
                        echo Html::a("Вход", yii\helpers\Url::toRoute("site/login"));
                } else {
                echo Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm();
                    
                        //echo Html::a(Yii::$app->user->identity->name, yii\helpers\Url::toRoute("site/logout"));
                }
                ?>
                
            </div>
        </div>            
    </header>    
    
    <?php $this->beginBody() ?>

    <div class="container">
        <?php
            echo $this->render('flashes');
        ?>
            <?= $content ?>
    </div>


<footer class="footer">
    <div class="container">
        <p><img src="/img/jmilogo.png" width=80px height=60px></p>
        <p class="pull-left">&copy; JMI <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?> <?= Yii::getVersion()?></p>
    </div>
</footer>    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>    