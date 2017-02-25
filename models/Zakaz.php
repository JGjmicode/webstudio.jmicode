<?php
namespace app\models;

class Zakaz extends \yii\db\ActiveRecord {
    

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

    public static function closeZakaz($id){
        $zakaz = self::findOne($id);
        $zakaz->status = true;
        if($zakaz->save()){
            return true;
        }else {
            return false;
        }
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

    /*function getTab1() {
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
    */
    public function attributeLabels() {
        
        return array(
            "id" => "id",
            
            "klient.name" => "Клиент",
            "klient_id" => "Клиент",
            "projectname" => "Наименование",
            "date_start" => "Дата начала",
            "date_end" => "Дата закрытия",
            "dead_line" => "Дэдлайн",
            "prim" => "Примечание",
            'sum' => 'Стоимость, руб.'
        );
    }

    public function rules()
    {
        return [[['projectname'], 'required'],
            [['date_start', 'dead_line', 'sum', 'prim', 'n_dog', 'date_dog', 'klient_id'], 'default']

        ];
    }
}
