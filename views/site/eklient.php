    <?php    
        use yii\helpers\Html;
        use yii\widgets\ActiveForm;
        
        $id;
        $klienName;
        $prim;
        $kontakt;
        $zakaz;
        
        foreach ($data as $val):
            $id = $val->id;
            $klientName = $val->name;
            $prim = $val->prim;     
            $kontakt = $val->kontakt;
            $zakaz = $val->zakaz;
        endforeach;        
    ?>
        <div class="container">
            <h2 class="h-title">Карточка клиента</h2>
            <div class="col-md-6">
                
                <table class="table">
                    <tr class="tbl-colored">
                        <td>ID</td>
                        <td><?= $id ?></td>
                    </tr><tr>
                        <td>Наименование</td>
                        <td><?= yii\helpers\Html::textInput("klient", $klientName, ["id"=>"klientName", "class" =>"form-control"]); ?></td>
                    </tr>                   
                </table>
            </div>
            <div class="col-md-6">
                <h4 class="h-projects">Проекты по клиенту</h4>
                 <?= Html::a("Новый", \yii\helpers\Url::to(["site/emptyz", "id"=>$id])); ?>
                <div class="list-group">                    
                    <?php
                        foreach ($zakaz as $val):
                            echo Html::a($val->projectname, \yii\helpers\Url::to(["site/ezakaz", "id"=>$val->id]), ["class"=>"list-group-item"]);
                        endforeach;
                        ?>
          </div>                
            </div>

            <div class="col-md-12">
                <h4>Контакты</h4>
                <table class="table">
                    <tr class="tbl-colored">
                        <th>#</th>
                        <th>Ф.И.О.</th>
                        <th>Телефон</th>
                        <th>e-mail</th>
                        <th>Skype</th>
                        <th>Примечание</th>
                        <th>Изменить</th>
                        <th>Удалить</th>
                    </tr>
                    <?php
                        foreach ($kontakt as $val):
                            echo "<tr>";
                            echo "<td>".$val->id."</td>";
                            echo "<td>".$val->name."</td>";
                            echo "<td>".$val->phone."</td>";
                            echo "<td>".$val->email."</td>";
                            echo "<td>".$val->skype."</td>";
                            echo "<td>".$val->prim."</td>";
                            echo "<td>".Html::button('Изменить', ["class" => "btn btn-default btn-xs"])."</td>";
                            echo "<td>".Html::button('Удалить', ["class" => "btn btn-danger btn-xs"])."</td>";
                            echo "</tr>";
                        endforeach;
                    ?>
                </table>
            </div>
            <div class="col-md-12">
                <?= Html::button('Добавить контакт', ["id" => "add-contact", "class" => "btn btn-default btn-right"]); ?>
                <?= Html::button('Сохранить', ["class" => "btn btn-default btn-right"]); ?>
            </div>        
    <div class="col-md-12">
        <p><label for="prim"><b>Примечание</b></label><?= yii\helpers\Html::textInput("prim", $prim, ["id" => "prim", "class" =>"form-control"]); ?></p>
    </div>       
            
    <div class="popup-input-window popup-contact">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Новый контакт</h3>
            </div>
            <div class="panel-body">
                <?php ActiveForm::begin(); ?>
                    <label for="name">Имя</label>
                    <?= Html::textInput("name","", ["id"=>"name", "class" =>"form-control", ""]); ?>
                    <label for="phone">Телефон</label>
                    <?= Html::textInput("phone","", ["id"=>"phone", "class" =>"form-control", ""]); ?>
                    <label for="mail">e-mail</label>
                    <?= Html::textInput("mail","", ["id"=>"mail", "class" =>"form-control", ""]); ?>                    
                    <label for="skype">Skype</label>
                    <?= Html::textInput("skype","", ["id"=>"skype", "class" =>"form-control", ""]); ?>                    
                    <label for="prim">Примечание</label>
                    <?= Html::textInput("prim","", ["id"=>"prim", "class" =>"form-control", ""]); ?>                    
            <?= Html::button('Отмена', ["id"=>"btn-cancel", "class" => "btn btn-default btn-right"]) ?> 
            <?= Html::submitButton("Сохранить", ["id"=>"btn-sbmt", "class" => "btn btn-default btn-right"]) ?>                    
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    
    
    <div class="overlay"></div>            
            
</div>

<!-- ***************************************  -->

<script>
    var btnAddContact = document.querySelector("#add-contact");
    var cancel = document.querySelector("#btn-cancel");
    var popup = document.querySelector(".popup-contact");
    var overlay = document.querySelector(".overlay");
    
    btnAddContact.addEventListener("click", function(){
        overlay.classList.add("modal-content-show");
        popup.classList.add("modal-content-show");        
    });
    cancel.addEventListener("click", function(){
       popup.classList.remove("modal-content-show");
       overlay.classList.remove("modal-content-show");            
    });
</script>