<?php

use yii\grid\GridView;

$this->title = 'Проекты';
?>

<h2 align="center">Список проектов</h2>

<?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'projectname'],
            ['attribute' => 'klient.name'],
            ['attribute' => 'date_start'],
            ['attribute' => 'dead_line'],
            ['attribute' => 'date_end'],
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

