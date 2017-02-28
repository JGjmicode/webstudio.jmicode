<?php

use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\alert\Alert;

$this->title = 'Проекты';
?>
<?php
if($session->has('message')) {
    echo Alert::widget([
        'type' => Alert::TYPE_SUCCESS,
        'title' => 'Well done!',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => $session->getFlash('message'),
        'showSeparator' => true,
        'delay' => 2000
    ]);
}

?>
<h2 align="center">Список проектов</h2>
<?= Html::a('Очистить фильтры', ['index'], ['class' => 'btn btn-info']) ?>
<?= Html::a('Добавить проект', ['/projects/add'], ["class" => "btn btn-default btn-left"]) ?>



<?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'projectname'],
            ['attribute' => 'klient.name'],
            ['attribute' => 'date_start',
                'filter' => DatePicker::widget([
                    'language' => 'ru',
                    'model' => $searchModel,
                    'attribute' => 'date_start_from',
                    'attribute2' => 'date_start_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ]),
            ],
            ['attribute' => 'dead_line',
                'filter' => DatePicker::widget([
                    'language' => 'ru',
                    'model' => $searchModel,
                    'attribute' => 'dead_line_from',
                    'attribute2' => 'dead_line_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ]),
            ],
            ['attribute' => 'date_end',
                'filter' => DatePicker::widget([
                    'language' => 'ru',
                    'model' => $searchModel,
                    'attribute' => 'date_end_from',
                    'attribute2' => 'date_end_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ]),
            ],
            ['attribute' => 'prim'],
            [
                'label' => 'Тикеты',
                'format' => 'raw',
                'value' => function($data){

                        return count($data->tiket);

                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ]
    ]);

