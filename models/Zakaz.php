<?php
namespace app\models;
use Yii;

class Zakaz extends \yii\db\ActiveRecord {
    

    public static function closeZakaz($id){
        $zakaz = self::findOne($id);
        $zakaz->status = true;
        $zakaz->date_end = date("Y-m-d");
        if($zakaz->save()){
            return true;
        }else {
            return false;
        }
    }

    public function addProject(){
        $this->create_by = Yii::$app->user->getId();

        if($this->save()){
            $related = new ZakazRelate();
            $related->zakaz_id = $this->id;
            $related->user_id = Yii::$app->user->getId();
            if($related->save()){
                return true;
            }else{
                return false;
            }
            
        }return false;
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

    public function getRelatedUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('zakaz_relate', ['zakaz_id' => 'id']);
    }

    public function attributeLabels() {
        
        return array(
            "id" => "ID",
            "status" => "",
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
