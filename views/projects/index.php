<?php

use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\alert\Alert;

$this->title = 'Проекты';
?>

<h2 align="center">Список проектов</h2>
<?= Html::beginTag("div", ["class" => "toolbar-panel"]) ?>
    <?= Html::a("<img src='/img/plus.png' >", ['/projects/add'], ["class" => "btn btn-default btn-left toolbar-button-round"]) ?>
<!--    --><?//= Html::a('Очистить фильтры', ['index'], ['class' => 'btn btn-info']) ?>
<?= Html::endTag("div") ?>



<?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'status',
                'format' => 'Html',
                'value' => function($data) {
                    if ($data['status'] == "0")
                        return Html::img("/img/pactive.png");
                    else
                        return Html::img("/img/pclose.png");
                }
            ],
            ['attribute' => 'id'],
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
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view}',
//            ],
        ],
        'tableOptions' => ['class' => "table table-main table-projects"]
    ]);

