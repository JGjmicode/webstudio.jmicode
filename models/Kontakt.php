<?php

namespace app\models;
use Yii;
use kartik\alert\Alert;

class Kontakt extends \yii\db\ActiveRecord{

    public function rules()
    {
        return [
            [['name', 'klient_id'], 'required'],
            [['skype', 'phone', 'email', 'prim'], 'default'],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'skype' => 'Skype',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'prim' => 'Примечание',
        ];
    }

    public static function getContact($id){
        $contact = self::findOne($id);
        if(!is_null($contact)){
            $arr = array();
            $arr['name'] = $contact->name;
            $arr['skype'] = $contact->skype;
            $arr['email'] = $contact->email;
            $arr['phone'] = $contact->phone;
            $arr['prim'] = $contact->prim;
            $arr['klient_id'] = $contact->klient_id;
            return $arr;
        }else{
            return false;
        }
    }
    
    public static function deleteContact($id){
        $contact = self::findOne($id);
        if(!is_null($contact)){
            $contact->delete();
            return true;

        }else {
            Yii::$app->session->addFlash(\kartik\alert\Alert::TYPE_DANGER, 'Контакт не найден');
            return false;
        }
    }

}

