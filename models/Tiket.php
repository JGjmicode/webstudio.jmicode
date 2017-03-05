<?php

namespace app\models;
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
            return $tiket->zakaz_id;
        }else{
            return false;
        }
    }

    public function rules()    {
        return [
            [['task_name', 'task_des', 'zakaz_id'], 'required'],
            [['priority_id', 'performer_id', 'dead_line'], 'default'],
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


}