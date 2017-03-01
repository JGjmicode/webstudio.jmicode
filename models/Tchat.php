<?php

namespace app\models;
use Yii;
use dosamigos\transliterator\TransliteratorHelper;

class Tchat extends \yii\db\ActiveRecord{

    public $uploadFile;
    
    public function getUsers() {
        return $this->hasOne(Users::className(), ["id"=>"user_id"]);
    }    

    /*function getTchat () {
        return Tiket::find()->joinWith("users")->all();
    }*/

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
            return true;
        }else{
            return false;
        }
    }
}