<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<div class="container">
    <?php         
            
        ActiveForm::begin(); ?>
        <div class="h-title"><h2>Новый заказчик</h2></div>
        <div class="empty-new">
            <label for="name">Наименование</label>
                <?= Html::textInput("name", "", ["class" =>"form-control", "id"=>"name", "placeholder"=>"text"]); ?>
            <label for="prim">Примечание</label>
                <?= Html::textInput("prim","", ["class" =>"form-control", "id" => "prim"]); ?>
            </div>
            <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt", "class" => "btn btn-default btn-right"]); ?>
    <?php
        ActiveForm::end();
    ?>
</div>