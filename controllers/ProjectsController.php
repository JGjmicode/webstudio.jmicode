<?php
namespace app\controllers;
use app\controllers\BehaviorsController;
use app\models\Tiket;
use app\models\Zakaz;
use app\models\ZakazSearch;
use Yii;
use yii\helpers\Url;

class ProjectsController extends BehaviorsController{

    public function actions()
    {
        $this->layout = "wpLayout";
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
        $searchModel = new ZakazSearch();
        $dataProvider  = $searchModel->search(Yii::$app->request->get());
        return $this->render('index', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id = NULL){
        if(!is_null($id)) {
            $zakaz = Zakaz::findOne($id);
            $tiket = new Tiket();
            if($zakaz->load(Yii::$app->request->post()) && $zakaz->save()){
                return $this->render('view', [
                    'zakaz' => $zakaz,
                    'tiket' => $tiket,
                    'success' => 'Изменения успешно сохранены!'
                ]);
            }
            if($tiket->load(Yii::$app->request->post()) && $tiket->validate()){
                $tiket->saveTiket($id);
            }
            return $this->render('view', [
                'zakaz' => $zakaz,
                'tiket' => $tiket,
            ]);
        }
    }
    
}