<?php 
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="container">
    <div class="toolbar clearfix">
        <?= Html::a("Добавить", \yii\helpers\Url::to(["site/emptyk"]), ["class" => "btn btn-success btn-right"]); ?>
    </div>
    <table class="table table-striped table-zakaz">
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Примечание</th>
            <th>Просмотр</th>
        </tr>
        
    <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row->id ?></td>
            <td><?= $row->name ?></td>
            <td><?= $row->prim ?></td>
            <td>
                <?php ActiveForm::begin(['action' => ['eklient'], 'method' => 'get']); ?> 
                    <?= Html::textInput('id',$row->id, ["class" => "hide"]); ?>                
                    <?= Html::submitButton('Просмотр', ["class" => "btn btn-default btn-xs"]) ?>            
                <?php ActiveForm::end(); ?>
            </td>
        </tr>
    <?php endforeach; ?>        
    </table>
</div>
