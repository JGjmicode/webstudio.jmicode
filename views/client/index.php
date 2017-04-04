<?php 
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

$this->title = 'Список клиентов';
?>
<h2 class="h-title">Список клиентов</h2>
<div class="container">
    <div class="toolbar clearfix">
        <?= Html::a("Добавить", \yii\helpers\Url::to(["client/add"]), ["class" => "btn btn-success btn-right"]); ?>
    </div>
    <table class="table table-striped table-client">
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Примечание</th>
            <th>Просмотр</th>
        </tr>
        
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><?= $client->id ?></td>
            <td><?= $client->name ?></td>
            <td><?= $client->prim ?></td>
            <td>
                <?=Html::a('Просмотр', ['/client/view', 'id' => $client->id], ["class" => "btn btn-default btn-xs"])?>

            </td>
        </tr>
    <?php endforeach; ?>        
    </table>
</div>
