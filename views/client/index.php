<?php 
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

$this->title = 'Список клиентов';
?>
<h2 class="h-title">Список клиентов</h2>
<div class="container">
    <?= Html::beginTag("div", ["class" => "toolbar-panel"]) ?>
        <?= Html::a("<img src='/img/add.png' >", \yii\helpers\Url::to(["client/add"]), ["class" => "btn btn-default"]); ?>
    <?= Html::endTag("div") ?>

    <table class="table table-main table-client">
        <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Примечание</th>
            <th>Просмотр</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($clients as $client): ?>
        <tr data-key="<?= $client->id ?>">
            <td ><?= $client->id ?></td>
            <td><?= $client->name ?></td>
            <td><?= $client->prim ?></td>
            <td>
                <?=Html::a('Просмотр', ['/client/view', 'id' => $client->id], ["class" => "btn btn-default btn-xs"])?>

            </td>
        </tr>
        </tbody>
    <?php endforeach; ?>        
    </table>
</div>
