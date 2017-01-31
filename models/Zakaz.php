<?php
namespace app\models;

class Zakaz extends \yii\db\ActiveRecord {
    
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    public static function tableName() {
        return "zakaz";
    }
    
    public static function primaryKey() {
        return array("id");
    }

    public function uploadFiles($file, $desc, $id) {
        $filePath = 'upload/';
        $date = strtotime('now');
        $fileName = 'P'.$id.'-'.$date.'_'.$file->name;
        $file->saveAs($filePath.$fileName);
        
        \Yii::$app->db->createCommand()->insert(
                'zakazfiles',
                [
                    'zakaz_id' => $id,
                    'path' => $filePath.$fileName,
                    'name' => $fileName,
                    'des' => $desc
                ])->execute();
    }
    
    public function setZakazPay($sum, $date, $prim, $id) {  
        \Yii::$app->db->createCommand()->insert(
                'oplata',
                [
                   'zakaz_id' => $id,
                    'oplata' => $sum,
                    'date_opl' => date_format(\DateTime::createFromFormat("d-m-Y", $date), "Y-m-d"),
                    'prim' => $prim,
                ]
                )->execute();                        
    }
    
    public function setTiket($title, $text, $id) {
        $userId = \Yii::$app->user->getId();
        \Yii::$app->db->createCommand()->insert(
                "tiket",
                [
                    'zakaz_id' => $id,
                    'user_id' => $userId,
                    'task_name' => $title,
                    'task_des' => $text,
                    'date_add' => date("Y-m-d"),
                ]
                )->execute();
    }
    
    public function setNewZakaz($params) {
        if ($params['date-start'] == "")
            $date1 = null;
        else             
            $date1 = date_format(\DateTime::createFromFormat("d-m-Y", $params['date-start']), "Y-m-d");
        if ($params['deadline'] == "")
            $date2 = null;
        else 
            $date2 = date_format(\DateTime::createFromFormat("d-m-Y", $params['deadline']), "Y-m-d");            
        if ($params['sum'] == "")
            $sum = 0;
        else
            $sum = $params['sum'];            
        
        \Yii::$app->db->createCommand()->insert(
                'zakaz', 
                [
                    'klient_id' => $params['klient'],
                    'projectname' => $params['name'],
                    'date_start' => $date1,
                    'dead_line' => $date2,
                    'sum' => $sum,
                    'prim' => $params['prim'],
                ])->execute();
        $id = \Yii::$app->db->getLastInsertID();
        return $id;
    }


    public function getKlient() {
        return $this->hasOne(Klient::className(), ["id" => "klient_id"]);
    }
    
    public function getTiket() {
        return $this->hasMany(Tiket::className(), ["zakaz_id" => "id"]);
    }
    
    public function getOplata() {
        return $this->hasMany(Oplata::className(), ["zakaz_id" => "id"]);
    }

    public function getZakazfiles() {
        return $this->hasMany(Zakazfiles::className(), ["zakaz_id" => "id"]);
    }

    function getTab1() {
        return Zakaz::find()->joinWith("klient")->joinWith("tiket")->all();
    }
    
    function getZakaz($id) {
        return Zakaz::find()->joinWith("klient")->joinWith("tiket")->joinWith("oplata")->joinWith("zakazfiles")->where("zakaz.id=".$id)->all();
    }
            
    function getTab() {
        $query = new \yii\db\Query();
        $query->addSelect(["zakaz.id", "klient.name", "projectname", "date_start", "date_end", "dead_line", "zakaz.prim"])
                ->from(Zakaz::tableName())
                ->leftJoin(Klient::tableName(), "zakaz.klient_id=klient.id")
                ->where("status=0");
        return $query->all();
    }
    
    public function attributeLabels() {
        
        return array(
            "id" => "id",
            
            "klient_id" => "klient_id",
            "projectname" => "projectname",
            "date_start" => "date_start",
            "date_end" => "date_end",
            "dead_line" => "dead_line",
            "prim" => "prim",
        );
    }
}
