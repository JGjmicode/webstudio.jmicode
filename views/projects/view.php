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


$this->title = 'Проект #'. $zakaz->id;
?>
<?php
    if(!is_null($message)){
        echo Alert::widget([
            'type' => Alert::TYPE_SUCCESS,
            'title' => 'Well done!',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $message,
            'showSeparator' => true,
            'delay' => 2000
        ]);
    }
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

        <?php $form = ActiveForm::begin([
            'id' => 'form-zakaz',
            'fieldConfig' => [
                'template' => '<div class="clearfix form-border"><div class="col-md-3">{label}</div><div class="col-md-9">{input}</div></div>',
                'labelOptions' => [],
            ],
        ]); ?>
        <?php echo $form->field($zakaz, 'projectname')?>
        <div class="clearfix form-border">
            <div class="col-md-4">
                Заказчик
            </div>
            <div class="col-md-8">
                <?= yii\helpers\Html::a($zakaz->klient->name, yii\helpers\Url::to(["site/eklient", "id"=>$zakaz->klient_id])); ?>
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

    </div>
    <div class="col-md-6">
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
    <?php
    ActiveForm::begin([
        'id' => 'close-project-form',
        'action' => Url::to(['/projects/close']),
    ]);?>
    <?= Html::hiddenInput('zakaz_id', $zakaz->id) ?>
    <?php ActiveForm::end() ?>
    <?php if($zakaz->status == 0){?>
        <!-- Скрыть кнопки если проект закрыт-->
        <div class="col-md-12">
            <?= Html::submitButton('Закрыть проект', ["class" => "btn btn-success btn-left", "form" => "close-project-form"]) ?>
            <?= Html::submitButton('Сохранить', ["class" => "btn btn-default btn-left", "form" => "form-zakaz"]) ?>
            <?= Html::button('Добавить тикет', ["id" => "add-tiket", "class" => "btn btn-default btn-left"]) ?>
        </div>
    <?php }?>
    <div class="clearfix"></div>
    <div class="col-md-6 files-panel-box">
        <div class="panel panel-default">
            <div class="panel-heading files-panel">Файлы</div>
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
                        echo Html::a($val->name, $val->path);
                        echo "</td>";
                        echo "<td>X</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php if($zakaz->status == 0){?>
            <!-- Скрыть форму добавления файлов если проект закрыт-->
            <div class="upload-files">
                <?php $uploadForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                <?= $uploadForm->field($zakazFiles, 'des', ['inputOptions' => ['id' => 'file-des', 'class' => 'form-control col-md-3']]) ?>
                <?= $uploadForm->field($zakazFiles, 'uploadFile')->fileInput() ?>
                <?= Html::submitButton('Загрузить', ["class" => "btn btn-default btn-left"]) ?>
                <?php ActiveForm::end() ?>
            </div>
        <?php }?>
    </div>

    <div class="col-md-6">
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
        <?php if($zakaz->status == 0){?>
            <!-- Скрыть кнопку добавления оплаты если проект закрыт-->
            <?= Html::button('Оплата', ["id"=>"btn-pay", "class" => "btn btn-default btn-right"]) ?>
        <?php }?>
    </div>

    <div class="popup-input-window popup-pay">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Оплата</h3>
            </div>
            <div class="panel-body">

                <?php \yii\widgets\ActiveForm::begin(); ?>

                <label for="sum">Сумма </label>
                <?= Html::textInput("sum","", ["id"=>"sum", "class" =>"form-control", ""]); ?>
                <span>Дата </span><?= DatePicker::widget([
                    'name' => 'date_pay',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'value' => '',
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ]); ?>
                <label for="prim1">Примечание </label><?= Html::textInput("prim1","", ["id"=>"prim1", "class" =>"form-control"]); ?>
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

