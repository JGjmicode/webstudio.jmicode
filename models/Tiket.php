<?php

namespace app\models;
use app\models\User;
use Yii;
use app\models\Zakaz;
use kartik\alert\Alert;

class Tiket extends \yii\db\ActiveRecord{

    const STATUS_ACTIVE = 1;
    
    public function getTchat() {
        return $this->hasMany(Tchat::className(), ["tiket_id"=>"id"]);
    }
    
    public function getUsers() {
        return $this->hasOne(User::className(), ["id"=>"user_id"]);
    }

    public function getPerformer(){
        return $this->hasOne(User::className(), ['id' => 'performer_id']);
    }

    public function getPriority(){
        return $this->hasOne(Priority::className(), ['id' => 'priority_id']);
    }

    public function getZakaz(){
        return $this->hasOne(Zakaz::className(),['id' => 'zakaz_id']);
    }

    public function saveTiket($id){
        if(!Zakaz::findOne($id)->status){
            $this->date_add = date("Y-m-d");
            $this->user_id = Yii::$app->user->getId();
            $this->zakaz_id = $id;
            if($this->save()){
                if(!is_null(User::findOne($this->performer_id)->e_mail)) {
                    $this->sendMessageNewTiket();
                }
                return true;
            }else {
                return false;
            }
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нельзя добавить задачу в выполненный проект');
            return false;
        }


    }
    
    public static function closeTiket($id){
        $tiket = self::findOne($id);
        $tiket->active = false;
        $tiket->date_close = date("Y-m-d");
        if($tiket->save()){
            $tiket->sendMessageCloseTiket();
            return $tiket->zakaz_id;
        }else{
            return false;
        }
    }

    public function rules()    {
        return [
            [['task_name', 'task_des', 'zakaz_id', 'performer_id'], 'required'],
            [['priority_id', 'dead_line'], 'default'],
        ];
    }
    
    public function attributeLabels()    {
        return [
            'task_name' => 'Заголовок',
            'task_des' => 'Описание',
            'priority_id' => 'Приоритет',
            'performer_id' => 'Исполнитель',
            'performer.name' => 'Исполнитель',
            'users.name' => 'Кем создана',
            'dead_line' => 'Крайний срок',
            'date_close' => 'Исполнение',
            'date_add' => 'Создана',
            'zakaz_id' => 'Проект',
        ];
    }

    public function sendMessageNewTiket(){
        $priority = !is_null($this->priority_id) ? Priority::findOne($this->priority_id)->priority. PHP_EOL : 'отсутствует'. PHP_EOL;
        $message = 'Заголовок: ' . $this->task_name. PHP_EOL;
        $message .= 'Описание: ' . $this->task_des. PHP_EOL;
        $message .= 'Кем добавлена: ' . User::findOne($this->user_id)->name. PHP_EOL;
        $message .= 'Название проекта: ' . Zakaz::findOne($this->zakaz_id)->projectname. PHP_EOL;
        $message .= 'Приоритет: '. $priority;
        $message .= 'Дата создания: ' . $this->date_add. PHP_EOL;
        $message .= 'Крайний срок: ' . $this->dead_line. PHP_EOL;
        Yii::$app->mailer->compose()
            ->setTo(User::findOne($this->performer_id)->e_mail)
            ->setSubject('Новая задача для проекта '. Zakaz::findOne($this->zakaz_id)->projectname)
            ->setTextBody($message)
            ->send();
    }
    
    public function setViewed(){
        $this->status = true;
        $this->save();
    }
    
    public static function getNewTiketForUser(){
        return self::find()->where(['status' => false, 'performer_id' => Yii::$app->user->getId()])->count();
    }

    public function sendMessageCloseTiket(){
        $users = array();
        if(!is_null($this->performer_id)){
            $users[] = User::findOne($this->performer_id);
        }
        if(!is_null($this->user_id)){
            $users[] = User::findOne($this->user_id);
        }

        $messages = array();
        foreach ($users as $user) {
            if (!is_null($user->e_mail)) {
                $messages[] = Yii::$app->mailer->compose('close-tiket-message', [
                    'id' => $this->id,
                ])
                    ->setSubject('Тикет № ' . $this->id . ' закрыт.')
                    ->setTo($user->e_mail);

            }
        }
        Yii::$app->mailer->sendMultiple($messages);

    }


}