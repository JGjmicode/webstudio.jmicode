<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Новый заказчик';
?>

<div class="container">
    <?php $clientForm = ActiveForm::begin(); ?>
    <div class="h-title"><h2>Новый заказчик</h2></div>
    <div class="empty-new">
    <?=$clientForm->field($client, 'name')?>
    <?=$clientForm->field($client, 'prim')?>
    </div>
    <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt", "class" => "btn btn-default btn-right"]); ?>
    <?php
    ActiveForm::end();
    ?>
</div>