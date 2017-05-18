<?php

namespace app\models;
use Yii;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Tchat extends \yii\db\ActiveRecord{

    public $uploadFile;
    
    public function getUsers() {
        return $this->hasOne(User::className(), ["id"=>"user_id"]);
    }    



    public function rules()
    {
        return [
            [['txt'], 'required'],
            [['uploadFile'], 'file'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'txt' => 'Сообщение',
            'uploadFile' => 'Прикрепить файл',
        ];
    }

    public function upload($name, $id)
    {
        $projectname = $this->transliterator($name);
        $path = 'upload/' . $projectname. '/tikets';
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        $fileName = $id.'_'.$this->uploadFile->baseName . '.' . $this->uploadFile->extension;
        if (file_exists($path . '/' . $fileName)) {
            $fileName = $id.'_'.$this->uploadFile->baseName . '_' . time() . '.' . $this->uploadFile->extension;
        }
        $attach = array(
            'attach_path' => $path . '/' . $fileName,
            'attach_name' => $fileName,
            'attach_ext' => $this->uploadFile->extension,
        );
        if ($this->validate()) {
            if($this->uploadFile->saveAs($path . '/' . $fileName)){

                if ($this->saveMessage($id, $attach)) {
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }
    }

    public function transliterator($name){

        $name = TransliteratorHelper::process($name, '', 'ru');
        $search = array(' ', '"', '\'');
        $replace = array('-', '', '');
        $name = str_replace($search, $replace, $name);
        $name = strtolower($name);
        return $name;

    }

    public function saveMessage($id, $attach = NULL){
        $this->tiket_id = $id;
        $this->user_id = Yii::$app->user->getId();
        $this->date_add = date("Y-m-d");
        if(!is_null($attach)) {
            $this->attach_path = '/'.$attach['attach_path'];
            $this->attach_name = $attach['attach_name'];
            $this->attach_ext = $attach['attach_ext'];
        }
        if($this->save()){
            $this->sendEMail();
            return true;
        }else{
            return false;
        }
    }

    public function sendEMail(){

        $e_mail = $this->getRecipient();
        if(!is_null($e_mail)) {

            Yii::$app->mailer->compose('tchat-new-message', [
                'tiket_id' => $this->tiket_id,
            ])
                ->setTo($e_mail)
                ->setSubject('Новое сообщение по тикету № '.$this->tiket_id .'.')
                ->send();
        }

    }

    public function getRecipient(){
        $tiket = Tiket::find()->where(['tiket.id' => $this->tiket_id])->joinWith('performer performer')->joinWith('users')->One();
        if($tiket->user_id == $this->user_id){
            if(!is_null($tiket->performer)) {
                return $tiket->performer->e_mail;
            }else return NULL;
        }elseif ($tiket->performer_id == $this->user_id){
            if(!is_null($tiket->users)) {
                return $tiket->users->e_mail;
            }else return NULL;
        }
        return NULL;
    }
}