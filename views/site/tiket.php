<?php

    
    use yii\helpers\Html;
    
    $user_id;
    $zakazId;
    $taskName;
    $taskDes;
    $dateAdd;
    $active;
    $chat;
    $user;
    file_put_contents('data.txt', var_export($data, true));
    foreach ($data as $val):
        $user_id = $val->user_id;
        $zakazId = $val->zakaz_id;
        $taskName = $val->task_name;
        $taskDes = $val->task_des;
        $dateAdd = $val->date_add;
        $dateClose = $val->date_close;
        $active = $val->active;
        $chat = $val->tchat;
        $user = $val->users;
        $tiketId = $val->id;
    endforeach;

?>


<div class="container">
          <h4><?= yii\helpers\Html::a("Вернуться к проекту", \yii\helpers\Url::to(["site/ezakaz", "id"=>$zakazId]), ["class"=>"label label-default"]) ?></h4>
            <?php
                if(!$active){
                    echo '<h4>Тикет закрыт '.DateTime::createFromFormat("Y-m-d", $dateClose)->format("d-m-Y").'</h4>';
                }
            ?>
          <div class="tiket-user col-md-2">
            <?= "<img src='".$user["avatar"]."' width=48 height=48></img><br>" ?>
            <?= $user["name"]; ?>
            <?= "<br><time>".DateTime::createFromFormat("Y-m-d", $dateAdd)->format("d-m-Y")."</time>" ?>
          </div>
          <div class="col-md-6">
          <div class="panel panel-default tiket-main">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $taskName ?></h3>
            </div>
            <div class="panel-body">
              <?= $taskDes ?>
            </div>        
        </div>
        </div>
          
          <div class="col-md-4 tiket-empty">              
          </div>              
              
          
          <?php
          foreach ($chat as $val): ?>
              <div class="col-md-1"></div>
              <div class="tiket-user col-md-2">
                    <?= "<img src='".$val->users["avatar"]."' width=48 height=48></img><br>" ?>
                    <?= $val->users["name"]; ?>
                    <?= "<br><time>".DateTime::createFromFormat("Y-m-d", $val->date_add)->format("d-m-Y")."</time>" ?>
              </div>
            <div class="col-md-6">
            <div class="panel panel-default tiket-main">
                <div class="panel-body">
                    <?= $val->txt ?>
                </div>              
            </div>
            </div>
              <div class="col-md-3 tiket-empty"></div>
          <?php endforeach; ?>
              <?php if($active){?>
              <div class="col-md-12">
                  <?= Html::a("Закрыть тикет", \yii\helpers\Url::to(["site/close-tiket", 'id' => $tiketId]), ["class" => "btn btn-success btn-right"])?>
                  <?= yii\helpers\Html::button("Добавить запись", ["id"=>"btn-new",  "class" => "btn btn-default btn-right"]) ?>                  
              </div>
              <?php }?>
    <div class="popup-input-window">
        <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Новая запись</h3>
        </div>  
        <div class="panel-body">
            <div class="popup-area">              
              <?php \yii\widgets\ActiveForm::begin();                
                echo yii\helpers\Html::textarea("text");  ?>
            </div>
            <?= Html::button('Отмена', ["id"=>"btn-cancel", "class" => "btn btn-default btn-right"]) ?> 
            <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt", "class" => "btn btn-default btn-right"]) ?>            
            <?php
                \yii\widgets\ActiveForm::end();
            ?>
            
        </div>
        </div>            
    </div>
    
    <div class="overlay"></div>              
              
</div>

<!-- *********************************************************************************** -->

<script>
    var btnNew = document.querySelector("#btn-new");
    var cancel = document.querySelector("#btn-cancel");
    var popup = document.querySelector(".popup-input-window");
    var overlay = document.querySelector(".overlay");   
    
    btnNew.addEventListener("click", function(){
        overlay.classList.add("modal-content-show");
        popup.classList.add("modal-content-show");
    });   
    cancel.addEventListener("click", function(){
       popup.classList.remove("modal-content-show");
       overlay.classList.remove("modal-content-show");
    });     
</script>