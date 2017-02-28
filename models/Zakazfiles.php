<?php
namespace app\models;
use dosamigos\transliterator\TransliteratorHelper;

class Zakazfiles extends \yii\db\ActiveRecord{

    public $uploadFile;

    public function rules(){
        return [
            [['uploadFile'], 'file'],
            [['des'], 'default'],
        ];
    }

    public function upload($name, $id)
    {
        $projectname = $this->transliterator($name);
        $path = 'upload/' . $projectname;
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        $fileName = $this->uploadFile->baseName . '.' . $this->uploadFile->extension;
        if (file_exists($path . '/' . $fileName)) {
            $fileName = $this->uploadFile->baseName . '_' . time() . '.' . $this->uploadFile->extension;
        }
        if ($this->validate()) {
            if($this->uploadFile->saveAs($path . '/' . $fileName)){
                $this->zakaz_id = $id;
                $this->path = '/'.$path . '/' . $fileName;
                $this->name = $fileName;
                $this->type = $this->uploadFile->extension;
                if ($this->save()) {
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

    public function attributeLabels(){
        return [
            'des' => 'Описание',
            'uploadFile' => 'Файл',

        ];
    }


}