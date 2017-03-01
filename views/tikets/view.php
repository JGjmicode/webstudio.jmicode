<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;
use app\models\FileTypesIcons;

$this->title = 'Задача  # '. $tiket->id .' к проекту ' .$tiket->zakaz->projectname;
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
<?=Html::tag('h2', 'Задача  # '. $tiket->id .' к проекту ' .$tiket->zakaz->projectname, ['class' => 'text-center'])?>
<div class="container">

          <h4 class="col-md-3"><?= yii\helpers\Html::a("Вернуться к проекту", Url::to(["projects/view", "id"=>$tiket->zakaz_id]), ["class"=>"label label-default"]) ?></h4>
            <h4 class="col-md-3">Приоритет: <?=(!is_null($tiket->priority)) ? $tiket->priority->priority : 'отсутствует'?></h4>
            <h4 class="col-md-3">Дэдлайн: <?=(!is_null($tiket->dead_line)) ? $tiket->dead_line : 'отсутствует'?></h4>
            <div class="clearfix"></div>
            <?php
                if(!$tiket->active){
                    echo '<h4>Тикет закрыт '.DateTime::createFromFormat("Y-m-d", $tiket->date_close)->format("d-m-Y").'</h4>';
                }
            ?>
          <div class="tiket-user col-md-2">
            <?= "<img src='/".$tiket->users->avatar."' width=48 height=48></img><br>" ?>
            <?= $tiket->users->name; ?>
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
                    <?= "<img src='/".$val->users->avatar."' width=48 height=48></img><br>" ?>
                    <?= $val->users->name; ?>
                    <?= "<br><time>".DateTime::createFromFormat("Y-m-d", $val->date_add)->format("d-m-Y")."</time>" ?>
              </div>
            <div class="col-md-6">
            <div class="panel panel-default tiket-main">
                <div class="panel-body">

                         <?php
                            if(!is_null($val->attach_path)){
                                echo '<div class="col-md-10">';
                                echo $val->txt;
                                echo '</div>';
                                echo '<div class="col-md-2">';
                                if($val->attach_ext == 'jpg' || $val->attach_ext == 'jpeg' || $val->attach_ext == 'png'){
                                    echo Html::a(Html::img($val->attach_path,['class' => 'pull-right img-thumbnail', 'width' => '40px']),[$val->attach_path], ['target' => '_blanc'] );
                                }else{
                                    echo Html::a(Html::img((FileTypesIcons::getIcon($val->attach_ext)) ? FileTypesIcons::getIcon($val->attach_ext) : '/img/icon-files/Default.png',['class' => 'pull-right img-thumbnail', 'width' => '40px']),['/tikets/download', 'path' => $val->attach_path]);
                                }
                                echo '</div>';
                            }else{
                                echo $val->txt;
                            }
                        ?>

                </div>
            </div>
            </div>
              <div class="col-md-3 tiket-empty"></div>
          <?php endforeach; ?>
              <?php if($tiket->active == 1 && $tiket->zakaz->status == 0){?>
              <div class="col-md-12">
                  <?php $tchatForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
                  <div class="col-md-6">
                      <?=$tchatForm->field($tchat, 'txt')->textarea(['rows' => 2, 'cols' => 5])?>
                  </div>
                  <div class="col-md-3">
                      <?= $tchatForm->field($tchat, 'uploadFile')->fileInput() ?>
                      <?= Html::submitButton('Отправить', ["class" => "btn btn-default btn-left"]) ?>

                  </div>
                  <div class="clearfix"></div>
                  <?= Html::a("Закрыть тикет", \yii\helpers\Url::to(["tikets/close-tiket", 'id' => $tiket->id]), ["class" => "btn btn-success btn-right"])?>
                  <?php ActiveForm::end() ?>
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