<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<?php
    echo 'Новое сообщение по тикету № '.$tiket_id .'.' .'<br>';
    echo 'Чтобы посмотреть сообщение '. Html::a('перейдите по ссылке', Url::toRoute(['tikets/view', 'id' => $tiket_id], true));
?>
