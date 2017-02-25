<?php
namespace app\models;
use app\models\Zakaz;
use yii\data\ActiveDataProvider;

class ZakazSearch extends Zakaz{

    public $date_end_from;
    public $date_end_to;
    public $date_start_from;
    public $date_start_to;
    public $dead_line_from;
    public $dead_line_to;

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

        $dataProvider->sort->attributes['klient.name'] = [
            'asc' => ['klient.name' => SORT_ASC],
            'desc' => ['klient.name' => SORT_DESC],
        ];
        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'projectname', $this->getAttribute('projectname')])
            ->andFilterWhere(['like', 'klient.name', $this->getAttribute('klient.name')])
            ->andFilterWhere(['like', 'zakaz.prim', $this->getAttribute('prim')])
            ->andFilterWhere(['between', 'date_start', $this->date_start_from, $this->date_start_to])
            ->andFilterWhere(['between', 'zakaz.date_end', $this->date_end_from, $this->date_end_to])
            ->andFilterWhere(['between', 'zakaz.dead_line', $this->dead_line_from, $this->dead_line_to]);

        return $dataProvider;
    }


    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['klient.name']);
    }

    public function rules()
    {
        return [
            [['projectname', 'klient.name', 'prim'], 'safe'],
            [['date_start_from', 'date_start_to'], 'safe'],
            [['date_end_from', 'date_end_to'], 'safe'],
            [['dead_line_from', 'dead_line_to'], 'safe'],
        ];
    }


}