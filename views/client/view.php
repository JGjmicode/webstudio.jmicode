<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Карточка клиента '. $client->name;
$url_contact = Url::to(['/client/get-contact']);
?>
<div class="container">

    <h2 class="h-title">Карточка клиента <?=$client->name?></h2>
    <div class="col-md-6">
        <?php
            $clientForm = ActiveForm::begin();
        ?>
        <table class="table">
            <tr class="tbl-colored">
                <td>ID</td>
                <td><?= $client->id ?></td>
            </tr><tr>
                <td>Наименование</td>
                <td><?=$clientForm->field($client, 'name')->label(false) ; ?></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h4 class="h-projects">Проекты по клиенту</h4>
        <?= Html::a("Новый", \yii\helpers\Url::to(["projects/add", "klient_id"=>$client->id])); ?>
        <div class="list-group">
            <?php
            foreach ($client->zakaz as $zakaz):
                echo Html::a($zakaz->projectname, \yii\helpers\Url::to(["site/ezakaz", "id"=>$zakaz->id]), ["class"=>"list-group-item"]);
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
            foreach ($client->kontakt as $kontakt):
                echo "<tr>";
                echo "<td>".$kontakt->id."</td>";
                echo "<td>".$kontakt->name."</td>";
                echo "<td>".$kontakt->phone."</td>";
                echo "<td>".$kontakt->email."</td>";
                echo "<td>".$kontakt->skype."</td>";
                echo "<td>".$kontakt->prim."</td>";
                echo "<td>".Html::button('Изменить', ['id' => 'edit-contact', 'contact-id' => $kontakt->id, "class" => "btn btn-default btn-xs"])."</td>";
                echo "<td>".Html::a('Удалить', Url::to(['/client/delete-contact', 'contact_id' => $kontakt->id, 'client_id' => $client->id]), ["class" => "btn btn-danger btn-xs"])."</td>";
                echo "</tr>";
            endforeach;
            ?>
        </table>
    </div>
    <div class="col-md-12">
        <?= Html::button('Добавить контакт', ["id" => "add-contact", "class" => "btn btn-default btn-right"]); ?>
        <?= Html::submitButton('Сохранить', ["class" => "btn btn-default btn-right"]) ?>
    </div>
    <div class="col-md-12">
        <?=$clientForm->field($client, 'prim')->textarea()->label(false) ; ?>
    </div>
    <?php
         ActiveForm::end();
    ?>
    <div class="popup-input-window popup-contact">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Новый контакт</h3>
            </div>
            <div class="panel-body">
                <?php $contactForm = ActiveForm::begin(); ?>
                <?=$contactForm->field($contact, 'klient_id')->hiddenInput(['value' => $client->id])->label(false)?>
                <?=$contactForm->field($contact, 'name')?>
                <?=$contactForm->field($contact, 'phone')?>
                <?=$contactForm->field($contact, 'email')?>
                <?=$contactForm->field($contact, 'skype')?>
                <?=$contactForm->field($contact, 'prim')->textarea()?>
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

