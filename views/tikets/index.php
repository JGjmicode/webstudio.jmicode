<?php

use yii\grid\GridView;
use app\models\Priority;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Zakaz;

$this->title = 'Задачи';

$authManager = \Yii::$app->getAuthManager();

?>


    <h2 align="center">Список задач</h2>
<?= Html::a('Очистить фильтры', ['index'], ['class' => 'btn btn-info']) ?>
<?= Html::button('Добавить тикет', ["id" => "add-tiket", "class" => "btn btn-default btn-left"]) ?>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model) {
        $class = '';
        if (!is_null($model->priority)) {
            if (!is_null($model->priority->class)) {
                $class = $model->priority->class;
            }else $class = 'info';
        }else{
            $class = 'info';
        }
        if(!$model->status){
            $class .= ' '. 'text-danger font-bold';
        }
        return ['class' => $class];

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
        ['attribute' => 'date_close',
            'filter' => DatePicker::widget([
            'language' => 'ru',
            'model' => $searchModel,
            'attribute' => 'date_close_from',
            'attribute2' => 'date_close_to',
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
?>

<div class="popup-input-window popup-tiket">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Новый тикет</h3>
        </div>
        <div class="panel-body">
            <?php $tiketForm = ActiveForm::begin(); ?>
            <?= $tiketForm->field($tiket, 'task_name')?>
            <?= $tiketForm->field($tiket, 'task_des')?>
            <?= $tiketForm->field($tiket, 'zakaz_id')->dropDownList(Zakaz::find()->select(['projectname', 'id'])->indexBy('id')->column(),
                ['prompt' => 'Выберите проект'])?>
            <?= $tiketForm->field($tiket, 'priority_id')->dropDownList(Priority::find()->select(['priority', 'id'])->indexBy('id')->column(),
                ['prompt' => 'Выберите приоритет'])?>
            <?= $tiketForm->field($tiket, 'performer_id')->dropDownList(User::find()->select(['name', 'id'])->indexBy('id')->column(),
                ['prompt' => 'Выберите Исполнителя'])?>

            <?= $tiketForm->field($tiket, 'dead_line')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ])
            ?>
            <?= Html::button('Отмена', ["id"=>"btn-cancel1", "class" => "btn btn-default btn-right"]) ?>
            <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt1", "class" => "btn btn-default btn-right"]) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="overlay"></div>


<script>
    var btnAddT = document.querySelector("#add-tiket");
    var cancel1 = document.querySelector("#btn-cancel1");
    var popupTiket = document.querySelector(".popup-tiket");
    var overlay = document.querySelector(".overlay");

    btnAddT.addEventListener("click", function() {
        overlay.classList.add("modal-content-show");
        popupTiket.classList.add("modal-content-show");
    });
    cancel1.addEventListener("click", function(){
        popupTiket.classList.remove("modal-content-show");
        overlay.classList.remove("modal-content-show");
    });


</script>
