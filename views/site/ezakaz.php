<?php
    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;
?>
<div class="container">
    <?php 
        $id;
        $clientid;
        $projectName;
        $clientName;
        $tiketsCount;
        $tikets;
        $dateStart;
        $deadLine;
        $prim;
        $status; 
        $price;
        $ndog;
        $dateDog;        
        $oplata;
        $files;
                
        
        foreach ($data as $val):
            $id = $val->id;
            $projectName = $val->projectname;
            $clientName = $val->klient->name;
            $klientid = $val->klient->id;
            $prim = $val->prim;
            $dateStart =  DateTime::createFromFormat("Y-m-d", $val->date_start);
            $deadLine = DateTime::createFromFormat("Y-m-d", $val->dead_line);
            $tiketsCount = count($val->tiket);
            $status = $val->status;
            $tikets = $val->tiket;
            $price = $val->sum;
            $ndog = $val->n_dog;
            $dateDog = $val->date_dog;            
            $oplata = $val->oplata;
            $files = $val->zakazfiles;
        endforeach;
    ?>
    <div class="col-md-6">
        <h4>Проект # <?= $id ?>  </h4>
        <p><?php 
                if ($status == 0)
                    echo "<span class='label label-success'>Активен</span>";
                else
                    echo "<span class='label label-default'>Закрыт</span>";
            ?> </p>
        <table class="table">
            <tr>
                <td><label for="projectName">Наименование</label></td>
                <td><?= yii\helpers\Html::textInput("klient", $projectName, ["id"=>"projectName", "class" =>"form-control"]); ?></td>
            </tr><tr>                
                <td>Заказчик</td> 
                <td><?= yii\helpers\Html::a($clientName, yii\helpers\Url::to(["site/eklient", "id"=>$klientid])); ?></td>
            </tr><tr>
                <td>Начало работ</td>
                <?php 
                    $dateStart = ($dateStart == "") ? $dateStart : $dateStart->format("d-m-Y");
                    $deadLine = ($deadLine == "") ? $deadLine : $deadLine->format("d-m-Y");
                ?>
                <td><?= DatePicker::widget([
                        'name' => 'date_start',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => $dateStart,
                        'language' => 'ru',
                        'pluginOptions' => [
                          'autoclose'=>true,
                          'format' => 'dd-mm-yyyy',
                          'todayHighlight' => true
                        ]                    
                ]); ?></td>
            </tr><tr>
                <td>Дэдлайн</td>
                <td><?= DatePicker::widget([
                        'name' => 'date_start',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => $deadLine,
                        'language' => 'ru',
                        'pluginOptions' => [
                          'autoclose'=>true,
                          'format' => 'dd-mm-yyyy',
                          'todayHighlight' => true
                        ]                    
                ]); ?></td>
            </tr><tr>
                <td><label for="price">Стоимость, руб.</label></td>
                <td><?= yii\helpers\Html::textInput("klient", $price, ["id"=>"price", "class" =>"form-control"]); ?></td>
            </tr><tr>
                <td><label for="dog">Договор</label></td>
                <td><?= yii\helpers\Html::textInput("dogovor", $ndog, ["id"=>"dog", "class" =>"form-control"]); ?>
                    от
                    <?= DatePicker::widget([
                        'name' => 'date_dog',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => $dateDog,
                        'language' => 'ru',
                        'pluginOptions' => [
                          'autoclose'=>true,
                          'format' => 'dd-mm-yyyy',
                          'todayHighlight' => true
                        ]                    
                ]); ?>
                </td>

        </table>
         
    </div>
    <div class="col-md-6">
          <h4><a href="#">Тикеты <span class="badge"><?= $tiketsCount ?></span></a></h4>          
          <div class="list-group">
            <?php
                foreach ($tikets as $val):
                    echo Html::a("<img class='tiket-avatar' src='".app\models\User::findIdentity($val->user_id)['avatar']."'></img> "
                            .$val->task_name." <time>".date_format(DateTime::createFromFormat("Y-m-d", $val->date_add), "d-m-Y")."</time>", 
                            \yii\helpers\Url::to(["site/tiket", "id"=>$val->id]), ["class"=>'list-group-item']);                    
                endforeach;              
            ?>
          </div>        
    </div>   
    
    <div class="col-md-12">
        <?= Html::button('Выполнено', ["class" => "btn btn-success btn-right"]) ?>
        <?= Html::button('Сохранить', ["class" => "btn btn-default btn-right"]) ?>         
        <?= Html::button('Добавить тикет', ["id" => "add-tiket", "class" => "btn btn-default btn-right"]) ?>        
    </div>
    
    <div class="col-md-12 files-panel-box">       
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
                foreach ($files as $val){
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
        <div class="upload-files">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <label for="file-des"><b>Примечание</b></label>
            <?= Html::textInput('file-des', "", ["id"=>"file-des", "class" => "form-control col-md-3"]); ?>
            <?= Html::fileInput('upFile'); ?>
                <button type="submit" class="btn btn-default">Отправить</button>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    
    <div class="col-md-12">
        <p><b>Оплаты</b><p>
        <table class="table table-striped" id="paytable">
            <tr>
                <th>#</th>
                <th>Сумма, руб.</th>
                <th>Дата</th>
                <th>Примечание</th>
            </tr>
            <?php
                foreach ($oplata as $val): ?>
            <tr>
                <td><?= $val->id ?></td>
                <td><?= $val->oplata ?></td>
                <td><?= $val->date_opl ?></td>
                <td><?= $val->prim ?></td>
            </tr>
                    
                <?php endforeach; ?>
        </table>
        <div>Остаток: <span class="rest"><?= $ss ?></span> руб.</div>
        <?= Html::button('Оплата', ["id"=>"btn-pay", "class" => "btn btn-default btn-right"]) ?> 
    </div>
    <div class="col-md-12">
        <p><label for="prim"><b>Примечание</b></label><?= yii\helpers\Html::textInput("klient", $prim, ["id" => "prim", "class" =>"form-control"]); ?></p>
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
                <?php ActiveForm::begin(); ?>
                    <label for="title">Заголовок</label>
                    <?= Html::textInput("title","", ["id"=>"title", "class" =>"form-control", ""]); ?>
                    <label for="text">Текст</label>
                    <?= Html::textarea("text") ?>
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

