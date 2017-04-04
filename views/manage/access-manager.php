<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$this->title = 'Разрешения для роли: '.$model->role;
?>

<div class="container">
    <h2 class="h-title">Разрешения для роли: <?=$model->role?></h2>
    <?php
        $form = ActiveForm::begin();
    ?>
    <?= Html::activeCheckboxList($model, 'permissions', $model->allPermissions, ['item'=>function ($index, $label, $name, $checked, $value){
        return '<div class="col-md-4">'.Html::checkbox($name, $checked, [
            'value' => $value,
            'label' => $label,

        ]).'</div>';
    }]) ?>
    <div class="clearfix"></div>
    <?= Html::submitButton('Сохранить', ['class' => 'submit']) ?>
    <?php
        ActiveForm::end();
    ?>
</div>
