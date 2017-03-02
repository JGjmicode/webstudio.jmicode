<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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
                    $customurl=Yii::$app->getUrlManager()->createUrl(['site/user-view','id'=>$model['id']]); //$model->id для AR
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
                    $customurl=Yii::$app->getUrlManager()->createUrl(['site/activate-user','id'=>$model->id]);
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
                    $customurl=Yii::$app->getUrlManager()->createUrl(['site/deactivate-user','id'=>$model->id]);
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