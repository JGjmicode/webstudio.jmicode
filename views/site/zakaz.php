<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="container">
    <div class="toolbar clearfix">
        <?= Html::a("Добавить", \yii\helpers\Url::to(["site/emptyz"]), ["class" => "btn btn-success btn-right"]); ?>
    </div>
    <table class="table table-striped table-zakaz">
        <tr>
            <th>#</th>
            <th>Клиент</th>
            <th>Наименование</th>
            <th>Начало</th>
            <th>Окончание</th>
            <th>Deadline</th>
            <th>Примечание</th>
            <th>Тикеты</th>                                 
            <th>Просмотр</th>
        </tr>    
       
        
       <?php foreach ($data as $row): ?>
        <tr>            
            <td><?= $row->id ?></td>
            <td><?= $row->klient->name ?></td>
            <td><?= $row->projectname ?></td>
            <td><?= ($row->date_start) ? DateTime::createFromFormat("Y-m-d", $row->date_start)->format("d-m-Y") : "-"; ?></td>
            <td><?=  ($row->date_end) ? DateTime::createFromFormat("Y-m-d", $row->date_end)->format("d-m-Y") : "-";  ?></td>
            <td><?= ($row->dead_line) ? DateTime::createFromFormat("Y-m-d", $row->dead_line)->format("d-m-Y") : "-"; ?></td>
            <td><?= $row->prim ?></td>
            <td><a href='#'><span class='badge'><?= count($row->tiket) ?></span></a></td>
            <td>
                <?php ActiveForm::begin(['action' => ['ezakaz'], 'method' => 'get']); ?> 
                    <?= Html::textInput('id',$row->id, ["class" => "hide"]); ?>                
                    <?= Html::submitButton('Просмотр', ["class" => "btn btn-default btn-xs"]) ?>            
                <?php ActiveForm::end(); ?>
            </td>
        </tr>

    <?php endforeach; ?>
 
    </table>
</div>
<?php $this->registerJSFile("js/wpScript.js"); ?>
