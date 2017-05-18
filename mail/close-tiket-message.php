<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<?php
echo 'Тикету № '.$id .' закрыт.' .'<br>';
echo Html::a('Перейти к тикету', Url::toRoute(['tikets/view', 'id' => $id], true));
?>
