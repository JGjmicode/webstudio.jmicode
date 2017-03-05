<?php
namespace app\models;
use app\models\User;
use yii\data\ActiveDataProvider;

class UsersSearch extends User{



    public static function tableName()
    {
        return 'users';
    }

    public function search($params){
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $query->distinct();


        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }


    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), []);
    }

    public function rules()
    {
        return [


        ];
    }


}