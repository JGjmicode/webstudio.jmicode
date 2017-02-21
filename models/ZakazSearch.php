<?php
namespace app\models;
use app\models\Zakaz;
use yii\data\ActiveDataProvider;

class ZakazSearch extends Zakaz{

    public static function tableName()
    {
        return 'zakaz';
    }

    public function search($params){
        $query = Zakaz::find();
        $dataProvider = new ActiveDataProvider([
           'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $query->joinWith('klient');
        $query->joinWith('tiket');
        $query->distinct();
        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }



}