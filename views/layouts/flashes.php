<?php
use kartik\alert\Alert;
?>
<?php foreach(Yii::$app->session->getAllFlashes() as $type => $messages): ?>
        <?php
    foreach ($messages as $message) {
        switch ($type) {
            case Alert::TYPE_SUCCESS:
                echo Alert::widget([
                    'type' => Alert::TYPE_SUCCESS,
                    'title' => 'Well done!',
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'body' => $message,
                    'showSeparator' => true,
                    'delay' => 2000
                ]);
                break;
            case Alert::TYPE_DANGER:
                echo Alert::widget([
                    'type' => Alert::TYPE_DANGER,
                    'title' => 'Oh snap!',
                    'icon' => 'glyphicon glyphicon-remove-sign',
                    'body' => $message,
                    'showSeparator' => true,
                    'delay' => 3000
                ]);
                break;
        }
    }
        ?>
    <?php endforeach ?>
