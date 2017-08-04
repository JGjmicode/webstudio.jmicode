<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\Priority;
use app\models\User;
use yii\helpers\Url;
use kartik\alert\Alert;
use app\models\Zakaz;
use app\models\FileTypesIcons;
use kartik\select2\Select2;
use yii\web\JsExpression;


$this->title = 'Проект #'. $zakaz->id;
?>


<div class="container">
    <div class="col-md-6">
    <h4>Проект # <?= $zakaz->id ?>  </h4>
    <p><?php
        if ($zakaz->status == 0)
            echo "<span class='label label-success'>Активен</span>";
        else
            echo "<span class='label label-default'>Закрыт</span>";
        ?> </p>
    </div>
    <div class="col-md-6">
        <h4>Пользователи проекта</h4>
        <?php
        foreach ($zakaz->relatedUsers as $relatedUser){
            echo "<span class='project-users'>";
            echo $relatedUser->name;
            echo Html::a('<span class="glyphicon glyphicon-remove remove-user"></span>', ['/projects/remove-related-user', 'zakaz_id' => $zakaz->id, 'user_id' => $relatedUser->id],
                ['title' => Yii::t('yii', 'Удалить'), 'data-pjax' => '0']);
            echo "</span>";
        }
        ?>

    </div>
    <div class="clearfix"></div>

    <?= Html::beginTag("div", ["class" => "toolbar-panel"]) ?>
        <?php if($zakaz->status == 0 && $editProject){?>
            <?= Html::submitButton("<img src='/img/save.png' >", ["class" => "btn btn-default btn-left", "form" => "form-zakaz"]) ?>
            <?= Html::button("<img src='/img/tickets.png' >", ["id" => "add-tiket", "class" => "btn btn-default btn-left"]) ?>
            <?= Html::submitButton("<img src='/img/closed.png' >", ["class" => "btn btn-default btn-left", "form" => "close-project-form"]) ?>
        <?php }?>

            <?php
            $url = Url::to(['/projects/get-users']);
            $zakazRelateForm = ActiveForm::begin(['action' => '/projects/add-related-user', 'options' => ['class'=>'add-user-form']]);
            ?>
            <?=$zakazRelateForm->field($zakazRelate, 'zakaz_id')->hiddenInput(['value' => $zakaz->id])->label(false)?>
            <?=$zakazRelateForm->field($zakazRelate, 'user_id')->widget(Select2::classname(), [
                'initValueText' => 'asdasdasd', // set the initial display text
                'options' => ['placeholder' => 'Найти пользователей ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => $url,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    //'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    //'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
            ])->label(false)?>

            <?= Html::submitButton("<img src='/img/user.png' >", ["class" => "btn btn-default btn-left"]) ?>
            <?php
            ActiveForm::end();
            ?>

    <?= Html::endTag("div") ?>

    <!-- сведения о проекте -->
    <div class="col-md-6">

        <?php $form = ActiveForm::begin([
            'id' => 'form-zakaz',
            'fieldConfig' => [
                'template' => '<div class="clearfix form-border"><div class="col-md-3">{label}</div><div class="col-md-9">{input}</div></div>',
                'labelOptions' => [],
            ],
        ]); ?>
        <?php echo $form->field($zakaz, 'projectname')?>
        <div class="clearfix form-border">
            <div class="col-md-3">
                Заказчик
            </div>
            <div class="col-md-9">
                <?= yii\helpers\Html::a($zakaz->klient->name, yii\helpers\Url::to(['/client/view', "id"=>$zakaz->klient_id])); ?>
            </div>
        </div>
        <?= $form->field($zakaz, 'date_start')->widget(DatePicker::classname(), [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ])
        ?>
        <?= $form->field($zakaz, 'dead_line')->widget(DatePicker::classname(), [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ])
        ?>
        <?php echo $form->field($zakaz, 'sum')?>

        <div class="clearfix form-border">
            <div class="col-md-3">
                Договор
            </div>
            <div class="col-md-9">
                <?= yii\helpers\Html::textInput('Zakaz[n_dog]', $zakaz->n_dog, ["id"=>"dog", "class" =>"form-control"]); ?>
                от
                <?= DatePicker::widget([
                    //'options' => ['class'=> 'col-md-3'],
                    'name' => 'Zakaz[date_dog]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $zakaz->date_dog,
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]); ?>
            </div>
        </div>
        <?php echo $form->field($zakaz, 'prim')->textarea()?>

        <?php ActiveForm::end();?>

        <!--   файлы -->
        <?php
        ActiveForm::begin([
            'id' => 'close-project-form',
            'action' => Url::to(['/projects/close']),
        ]);?>
        <?= Html::hiddenInput('zakaz_id', $zakaz->id) ?>
        <?php ActiveForm::end() ?>

        <div class="clearfix"></div>

        <div class="files-panel-box">
            <div class="panel panel-default">
                <div class="panel-heading files-panel"><strong>Файлы</strong></div>
                <div class="panel-body files-body">
                    <table class="table-files">
                        <tr>
                            <th>Примечание</th>
                            <th>Файл</th>
                            <th>Х</th>
                        </tr>
                        <?php
                        foreach ($zakaz->zakazfiles as $val){
                            echo "<tr>";
                            echo "<td>".$val->des."</td>";
                            echo "<td>";
                            echo Html::a(Html::img((FileTypesIcons::getIcon($val->type)) ? FileTypesIcons::getIcon($val->type) : '/img/icon-files/Default.png' ,['alt' => $val->name,'width' => '24px']), $val->path, ['target' => '_blanc']);
                            echo "</td>";
                            echo "<td>X</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php if($zakaz->status == 0 && $editProject){?>
                <!-- Скрыть форму добавления файлов если проект закрыт-->
                <div class="upload-files">
                    <?php $uploadForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                    <?= $uploadForm->field($zakazFiles, 'uploadFile')->fileInput() ?>
                    <?= $uploadForm->field($zakazFiles, 'des', ['inputOptions' => ['id' => 'file-des', 'class' => 'form-control col-md-3']]) ?>
                    <?= Html::submitButton('Загрузить', ["class" => "btn btn-default btn-left"]) ?>
                    <?php ActiveForm::end() ?>
                </div>
            <?php }?>

            <p><b>Оплаты</b><p>
            <table class="table table-striped" id="paytable">
                <tr>
                    <th>#</th>
                    <th>Сумма, руб.</th>
                    <th>Дата</th>
                    <th>Примечание</th>
                </tr>
                <?php
                foreach ($zakaz->oplata as $val): ?>
                    <tr>
                        <td><?= $val->id ?></td>
                        <td><?= $val->oplata ?></td>
                        <td><?= $val->date_opl ?></td>
                        <td><?= $val->prim ?></td>
                    </tr>

                <?php endforeach; ?>
            </table>
            <div>Остаток: <span class="rest">0</span> руб.</div>
            <?php if($zakaz->status == 0 && $editProject){?>
                <!-- Скрыть кнопку добавления оплаты если проект закрыт-->
                <?= Html::button('Оплата', ["id"=>"btn-pay", "class" => "btn btn-default btn-right"]) ?>
            <?php }?>
        </div>


    </div>
<!--  список тикеты -->
    <div class="col-md-6">
        <div id="tikets">
            <h4><a href="<?= Url::to(['/tikets/index', 'TiketSearch[zakaz.projectname]' => $zakaz->projectname])?>">Тикеты <span class="badge"><?= count($zakaz->tiket) ?></span></a></h4>
            <div class="list-group">
                <?php
                foreach ($zakaz->tiket as $val):
                    if(!$val->active){
                        $tiketClose =  "<img class='tiket-avatar' src='/img/done.png'></img> <time>".date_format(DateTime::createFromFormat("Y-m-d", $val->date_close), "d-m-Y")."</time>";
                    }else $tiketClose = '';
                    echo Html::a("<img class='tiket-avatar' src='/".app\models\User::findIdentity($val->user_id)['avatar']."'></img> "
                        .$val->task_name." <time>".date_format(DateTime::createFromFormat("Y-m-d", $val->date_add), "d-m-Y")."</time>".$tiketClose,
                        \yii\helpers\Url::to(["tikets/view", "id"=>$val->id]), ["class"=>'list-group-item']);
                endforeach;
                ?>
            </div>
        </div>

<!--        <div id="related-user">-->
<!--            <h4>Пользователи проекта</h4>-->
<!--            <div class="col-md-6">-->
<!--                <table>-->
<!--                    <tr>-->
<!--                        <th class="related-user-name">Имя</th>-->
<!--                        <th class="related-user-delete">Удалить</th>-->
<!--                    </tr>-->
<!--                --><?php
//                    foreach ($zakaz->relatedUsers as $relatedUser){
//                        echo '<tr>';
//                        echo '<td class="related-user-name">';
//                        echo $relatedUser->name;
//                        echo '</td>';
//                        echo '<td class="related-user-delete">';
//                        echo Html::a('<span class=" glyphicon glyphicon-remove"></span>', ['/projects/remove-related-user', 'zakaz_id' => $zakaz->id, 'user_id' => $relatedUser->id],
//                        ['title' => Yii::t('yii', 'Удалить'), 'data-pjax' => '0']);
//
//                        echo '</td>';
//                        echo '</tr>';
//                    }
//                ?>
<!--                </table>    -->
<!--            </div>-->
<!--            -->
<!--        </div>-->
    </div>






    <div class="popup-input-window popup-pay">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Оплата</h3>
            </div>
            <div class="panel-body">

                <?php $oplataForm = ActiveForm::begin(); ?>

                <?=$oplataForm->field($oplata, 'oplata')?>
                <?= $oplataForm->field($oplata, 'date_opl')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ])
                ?>
                <?=$oplataForm->field($oplata, 'prim')?>
                <?=$oplataForm->field($oplata, 'zakaz_id')->hiddenInput(['value' => $zakaz->id])->label(false, ['style'=>'display:none'])?>
                <?= Html::button('Отмена', ["id"=>"btn-cancel", "class" => "btn btn-default btn-right"]) ?>
                <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt", "class" => "btn btn-default btn-right"]) ?>

                <?php \yii\widgets\ActiveForm::end(); ?>

            </div>
        </div>
    </div>
    <div class="popup-input-window popup-tiket">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Новый тикет</h3>
            </div>
            <div class="panel-body">
                <?php $tiketForm = ActiveForm::begin(); ?>
                <?= $tiketForm->field($tiket, 'task_name')?>
                <?= $tiketForm->field($tiket, 'task_des')?>
                <?= $tiketForm->field($tiket, 'zakaz_id')->hiddenInput(['value' => $zakaz->id])->label(false, ['style'=>'display:none'])?>
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
</div>

<!-- *********************************************************************************** -->

<script>
    var btnAddT = document.querySelector("#add-tiket");
    var btnPay = document.querySelector("#btn-pay");
    var cancel = document.querySelector("#btn-cancel");
    var cancel1 = document.querySelector("#btn-cancel1");
    var popupPay = document.querySelector(".popup-pay");
    var popupTiket = document.querySelector(".popup-tiket");
    var overlay = document.querySelector(".overlay");
    var btnSbmt = document.querySelector("#btn-sbmt");
    var rest = document.querySelector(".rest");
    var sum = document.querySelector("#price");
    var tab = document.querySelector("#paytable");
    var filesPanel = document.querySelector(".files-panel");
    var filesBody = document.querySelector(".files-body");

    filesPanel.addEventListener("click", function(){
        filesBody.classList.toggle("files-body-show");
    });

    btnPay.addEventListener("click", function(){
        overlay.classList.add("modal-content-show");
        popupPay.classList.add("modal-content-show");
    });
    cancel.addEventListener("click", function(){
        popupPay.classList.remove("modal-content-show");
        overlay.classList.remove("modal-content-show");
        //rest.innerHTML = sum.value;
        calcRest();
    });
    btnAddT.addEventListener("click", function() {
        overlay.classList.add("modal-content-show");
        popupTiket.classList.add("modal-content-show");
    });
    cancel1.addEventListener("click", function(){
        popupTiket.classList.remove("modal-content-show");
        overlay.classList.remove("modal-content-show");
    });

    function calcRest() {
        var tr = tab.querySelectorAll("tr");
        var summa = parseFloat(sum.value);
        console.log(summa);
        for (var i = 1; i < tr.length; i++){
            var td = tr[i].getElementsByTagName("td");
            summa -= td[1].innerText;
        }
        if (summa > 0) rest.classList.add("rest-red");
        rest.innerHTML = summa;
    }
    window.onload = function() {
        calcRest();
    };

</script>

