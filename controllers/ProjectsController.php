<?php
namespace app\controllers;
use app\controllers\BehaviorsController;
use app\models\Tiket;
use app\models\Zakaz;
use app\models\ZakazSearch;
use Yii;
use yii\helpers\Url;
use app\models\Zakazfiles;
use yii\web\UploadedFile;
class ProjectsController extends BehaviorsController{

    public $message = NULL;

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

    public function actionIndex($success = NUll){
        $searchModel = new ZakazSearch();
        $dataProvider  = $searchModel->search(Yii::$app->request->get());
        if(!is_null($success)){
            $success = 'Проект успешно добавлен';
        }
        return $this->render('index', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
           'success' => $success,
        ]);
    }

    public function actionAdd(){
        $zakaz = new Zakaz();
        if($zakaz->load(Yii::$app->request->post()) && $zakaz->save()){
            return $this->redirect(['/projects/index', 'success' => '']);
        }

        return $this->render('add', [
            'zakaz' => $zakaz,
        ]);
    }

    public function actionView($id = NULL){
        if(!is_null($id)) {
            $zakaz = Zakaz::findOne($id);
            $tiket = new Tiket();
            $tiket->zakaz_id = $id;
            $zakazFiles = new Zakazfiles();

            if($zakaz->load(Yii::$app->request->post()) && $zakaz->save()){
                $this->message = 'Изменения успешно сохранены!';
            }

            if($tiket->load(Yii::$app->request->post()) && $tiket->validate()){
                $tiket->saveTiket($id);
                $this->message = 'Задача успешно добавлена!';
            }

            if($zakazFiles->load(Yii::$app->request->post()) && $zakazFiles->validate()) {
                $zakazFiles->uploadFile = UploadedFile::getInstance($zakazFiles, 'uploadFile');
                if ($zakazFiles->upload($zakaz->projectname, $id)) {
                    $this->message = 'Файл успешно загружен!';
                }
            }
            return $this->render('view', [
                'zakaz' => $zakaz,
                'tiket' => $tiket,
                'zakazFiles' => $zakazFiles,
                'message' => $this->message,

            ]);
        }else {
            return $this->redirect(['/projects/index']);
        }
    }

    public function actionClose(){
        $zakaz_id = Yii::$app->request->post('zakaz_id');
        if(!is_null($zakaz_id)){
            if(Zakaz::closeZakaz($zakaz_id)){
                return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
            }
        }
    }
    
}