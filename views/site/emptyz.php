<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use kartik\date\DatePicker;
?>
<div class="container">
    <?php 
        
        $klient = \app\models\Klient::find()->all();
        $items = ArrayHelper::map($klient,'id','name');
            
        ActiveForm::begin(); ?>
        <div class="h-title"><h2>Новый проект</h2></div>
        <div class="empty-new">
            <label for="name">Наименование </label>
                <?= Html::textInput("name", "", ["class" =>"form-control", "id"=>"name", "placeholder"=>"text"]); ?>
            <label>Заказчик</label>                
                <?= Html::dropDownList("klient", $id, $items, ["class" =>"form-control"]); ?>
            <label>Дата начала работ</label>
            <?= DatePicker::widget([
                        'name' => 'date-start',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => '',
                        'language' => 'ru',
                        'pluginOptions' => [
                          'autoclose'=>true,
                          'format' => 'dd-mm-yyyy',
                          'todayHighlight' => true
                        ]                    
                ]); ?>
            <label>Дэдлайн</label>
            <?= DatePicker::widget([
                        'name' => 'deadline',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => '',
                        'language' => 'ru',
                        'pluginOptions' => [
                          'autoclose'=>true,
                          'format' => 'dd-mm-yyyy',
                          'todayHighlight' => true
                        ]                    
                ]); ?>
            <label for="sum">Сумма</label>
                <?= Html::textInput("sum","", ["class" =>"form-control", "id" => "sum"]); ?>
            <label for="prim">Примечание</label>
                <?= Html::textInput("prim","", ["class" =>"form-control", "id" => "prim"]); ?>
            </div>
            <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt", "class" => "btn btn-default btn-right"]); ?>
    <?php
        ActiveForm::end();
    ?>
</div>