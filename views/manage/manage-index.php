<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Профили пользователей';
?>

<h2 class="text-center">Профили пользователей</h2>

<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model) {
        if ($model->status) {
                return ['class' => 'success'];
            }else return ['class' => 'warning'];

    }    ,
    'columns' => [
        ['attribute' => 'name'],
        ['attribute' => 'skype'],
        ['attribute' => 'phone'],
        ['attribute' => 'e_mail'],
        ['attribute' => 'role'],
        ['attribute' => 'last_login'],

        [
            'class' => 'yii\grid\ActionColumn',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    $customurl=Yii::$app->getUrlManager()->createUrl(['/manage/user-view','id'=>$model['id']]); //$model->id для AR
                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                        ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                }
            ],
            'template' => '{view}',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'buttons'=>[
                'activate'=>function ($url, $model) {
                    $customurl=Yii::$app->getUrlManager()->createUrl(['/manage/activate-user','id'=>$model->id]);
                    if($model->status){
                        return null;
                    }else {
                        return \yii\helpers\Html::a('<span class="glyphicon glyphicon-ok"></span>', $customurl,
                            ['title' => Yii::t('yii', 'Активировать'), 'data-pjax' => '0']);
                    }
                }
            ],
            'template' => '{activate}',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'buttons'=>[
                'activate'=>function ($url, $model) {
                    $customurl=Yii::$app->getUrlManager()->createUrl(['/manage/deactivate-user','id'=>$model->id]);
                    if(!$model->status){
                        return null;
                    }else {
                        return \yii\helpers\Html::a('<span class=" glyphicon glyphicon-remove"></span>', $customurl,
                            ['title' => Yii::t('yii', 'Деактивировать'), 'data-pjax' => '0']);
                    }
                }
            ],
            'template' => '{activate}',
        ],
    ]
]);
?>
<div class="col-md-6">
    <h2 class="text-center">Роли пользователей</h2>
    <table class="table table-striped table-roles">
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Разрешения</th>
        </tr>
    <?php
    foreach ($model->roles as $role => $param) {?>
        <tr>
            <td><?=$role?></td>
            <td><?=$param->description?></td>
            <td><?=Html::a('Разрешения', ['/manage/access-manager', 'role' => $role], ["class" => "btn btn-default btn-xs"])?></td>

        </tr>
    <?php }?>
    </table>
    <hr>
    <?php
        $roleForm = ActiveForm::begin();
    ?>
    <?=$roleForm->field($model, 'action')->hiddenInput(['value' => 'newRole'])->label(false)?>
    <div class="col-md-6">
        <?=$roleForm->field($model, 'newRole')?>
    </div>
    <div class="col-md-6">
        <?=$roleForm->field($model, 'newRoleDescription')?>
    </div><div class="col-md-4">
        <?= Html::submitButton('Добавить', ['class' => 'submit']) ?>
    </div>
    <?php
    ActiveForm::end();
    ?>
    <div class="clearfix"></div>
</div>
<div class="col-md-6 vertical-scrolling">
    <h2 class="text-center">Разрешения</h2>
    <table class="table table-striped table-permissions">
        <tr>
            <th>Название</th>
            <th>Описание</th>
        </tr>
        <?php
        foreach ($model->allPermissions as $name => $description) {?>
            <tr>
                <td><?=$name?></td>
                <td><?=$description?></td>


            </tr>
        <?php }?>
    </table>

</div>

