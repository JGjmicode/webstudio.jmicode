<?php
use yii\helpers\Html;
use yii\helpers\Url;


?>

<div class="container">
          <h4><?= yii\helpers\Html::a("Вернуться к проекту", Url::to(["projects/view", "id"=>$tiket->zakaz_id]), ["class"=>"label label-default"]) ?></h4>
            <?php
                if(!$tiket->active){
                    echo '<h4>Тикет закрыт '.DateTime::createFromFormat("Y-m-d", $tiket->date_close)->format("d-m-Y").'</h4>';
                }
            ?>
          <div class="tiket-user col-md-2">
            <?= "<img src='/".$tiket->users["avatar"]."' width=48 height=48></img><br>" ?>
            <?= $tiket->users["name"]; ?>
            <?= "<br><time>".DateTime::createFromFormat("Y-m-d", $tiket->date_add)->format("d-m-Y")."</time>" ?>
          </div>
          <div class="col-md-6">
          <div class="panel panel-default tiket-main">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $tiket->task_name ?></h3>
            </div>
            <div class="panel-body">
              <?= $tiket->task_des ?>
            </div>        
        </div>
        </div>
          
          <div class="col-md-4 tiket-empty">              
          </div>              
              
          
          <?php
          foreach ($tiket->tchat as $val): ?>
              <div class="col-md-1"></div>
              <div class="tiket-user col-md-2">
                    <?= "<img src='/".$val->users["avatar"]."' width=48 height=48></img><br>" ?>
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
              <?php if($tiket->active){?>
              <div class="col-md-12">
                  <?= Html::a("Закрыть тикет", \yii\helpers\Url::to(["tikets/close-tiket", 'id' => $tiket->id]), ["class" => "btn btn-success btn-right"])?>
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