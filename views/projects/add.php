<?php
use yii\widgets\ActiveForm;
use app\models\Klient;
use kartik\date\DatePicker;
use yii\helpers\Html;

$this->title = 'Новый заказ';


?>

<h2 align="center">Новый заказ</h2>

<?php $form = ActiveForm::begin()?>
<div class="col-md-4">
    <?=$form->field($zakaz, 'projectname')?>
</div>
<div class="col-md-4">
    <?=$form->field($zakaz, 'klient_id')->dropDownList(Klient::find()->select(['name' ,'id'])->indexBy('id')->column(),
        ['prompt' => 'Выберите клиента'])?>
</div>
<div class="col-md-4">
    <?=$form->field($zakaz, 'sum')?>
</div>
<div class="clearfix"></div>
<div class="col-md-4">
    <?= $form->field($zakaz, 'date_start')->widget(DatePicker::classname(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'language' => 'ru',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ])?>
</div>
<div class="col-md-4">
    <?= $form->field($zakaz, 'dead_line')->widget(DatePicker::classname(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'language' => 'ru',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ])?>
</div>
<div class="col-md-4">
    <?=$form->field($zakaz, 'prim')->textarea()?>
</div>

<?= Html::submitButton("Добавить", ["class" => "btn btn-primary btn-right"]) ?>
<?php ActiveForm::end(); ?>
