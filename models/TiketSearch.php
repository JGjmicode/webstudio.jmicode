<?php
namespace app\models;
use app\models\Tiket;
use yii\data\ActiveDataProvider;

class TiketSearch extends Tiket{

    public $date_add_from;
    public $date_add_to;
    public $date_close_from;
    public $date_close_to;
    public $dead_line_to;
    public $dead_line_from;

    public static function tableName()
    {
        return 'tiket';
    }

    public function search($params){
        
        $query = Tiket::find();



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        
        $query->joinWith('users');
        $query->joinWith('zakaz');
        $query->joinWith('performer performer');
        $query->joinWith('priority');

        $dataProvider->sort->attributes['zakaz.projectname'] = [
            'asc' => ['zakaz.projectname' => SORT_ASC],
            'desc' => ['zakaz.projectname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['users.name'] = [
            'asc' => ['users.name' => SORT_ASC],
            'desc' => ['users.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['performer.name'] = [
            'asc' => ['performer.name' => SORT_ASC],
            'desc' => ['performer.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['priority.priority'] = [
            'asc' => ['priority.priority' => SORT_ASC],
            'desc' => ['priority.priority' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'zakaz.projectname', $this->getAttribute('zakaz.projectname')])
            ->andFilterWhere(['like', 'users.name', $this->getAttribute('users.name')])
            ->andFilterWhere(['like', 'task_name', $this->getAttribute('task_name')])
            ->andFilterWhere(['like', 'priority.id', $this->getAttribute('priority.priority')])
            ->andFilterWhere(['like', 'performer.name', $this->getAttribute('performer.name')])
            ->andFilterWhere(['like', 'active', $this->getAttribute('active')])
            ->andFilterWhere(['between', 'date_add', $this->date_add_from, $this->date_add_to])
            ->andFilterWhere(['between', 'date_close', $this->date_close_from, $this->date_close_to])
            ->andFilterWhere(['between', 'tiket.dead_line', $this->dead_line_from, $this->dead_line_to]);


        return $dataProvider;
    }

    public function rules()
    {
        return [
            [['zakaz.projectname', 'task_name', 'users.name',
                'performer.name', 'priority.priority',
                'date_add', 'date_add_to', 'date_add_from',
                'active', 'date_close_from', 'date_close_to',
                'dead_line_from', 'dead_line_to' ], 'safe'],
            ];
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['zakaz.projectname' , 'users.name', 'performer.name', 'priority.priority']);
    }


}