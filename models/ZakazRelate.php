<?php
namespace app\models;
use yii\db\ActiveRecord;
use Yii;
use kartik\alert\Alert;

class ZakazRelate extends ActiveRecord{

    public static function tableName()
    {
        return 'zakaz_relate';
    }

    public function rules()
    {
        return [
            [['zakaz_id', 'user_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
        ];
    }

    public static function removeRelatedUser($user_id, $zakaz_id){
        $zakaz = Zakaz::findOne($zakaz_id);
        if(!is_null($zakaz) && $zakaz->create_by != $user_id){
            $related = self::find()->where(['zakaz_id' => $zakaz_id, 'user_id' => $user_id])->one();
            return $related->delete();
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нельзя удалить менеджера!');
            return false;
        }

    }

    public function addRelatedUser(){
        if(is_null(self::find()->where(['zakaz_id' => $this->zakaz_id, 'user_id' => $this->user_id])->one())){
            $this->save();
            return true;
        }
        return false;
    }
}