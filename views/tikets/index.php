<?php

use yii\grid\GridView;
use app\models\Priority;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;

$this->title = 'Задачи';
?>

    <h2 align="center">Список задач</h2>

<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        //file_put_contents('model.txt', var_export($model, true));
        //file_put_contents('key.txt', var_export($key, true));
        //file_put_contents('index.txt', var_export($index, true));
        //file_put_contents('grid.txt', var_export($grid, true));
        if (!is_null($model->priority)) {
            switch ($model->priority->id) {
                case 1:
                    return ['class' => 'warning'];
                    break;
                case 2:
                    return ['class' => 'danger'];
                    break;
                case 3:
                    return ['class' => 'success'];
                    break;
            }
            return ['class' => 'test'];
        }else {
            return ['class' => 'info'];
        }
    }    ,
    'columns' => [
        ['attribute' => 'id'],
        [
            'attribute'=>'priority.priority',
            'format'=>'text', // Возможные варианты: raw, html
            'content'=>function($data){
                if(!is_null($data->priority)) {
                    return $data->priority->priority;
                }else{
                    return NULL;
                }
            },
           'filter' => ArrayHelper::map(Priority::find()->all(), 'id', 'priority')
        ],
        ['attribute' => 'task_name'],

        ['attribute' => 'date_add',
            'filter' => DatePicker::widget([
                'language' => 'ru',
                'model' => $searchModel,
                'attribute' => 'date_add_from',
                'attribute2' => 'date_add_to',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => ['format' => 'yyyy-mm-dd']
            ]),
        ],
        ['attribute' => 'date_close'],
        ['attribute' => 'dead_line'],
        ['attribute' => 'users.name'],
        ['attribute' => 'performer.name'],
        ['attribute' => 'zakaz.projectname'],
        [
            'attribute'=>'active',
            'format'=>'raw', // Возможные варианты: raw, html
            'content'=>function($data){
                if(!$data->active) {
                    return Html::img('/img/done.png', ['style' => 'width:25px;']);

                }else{
                    return Html::img('/img/fail.png', ['style' => 'width:25px;']);
                }
            },
            'filter' => array("1"=>"Открыт","0"=>"Закрыт"),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
        ],
    ]
]);

