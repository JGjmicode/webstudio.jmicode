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
use app\models\Oplata;
use kartik\alert\Alert;
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

    public function actionAdd($klient_id = null){
        $zakaz = new Zakaz();
        $zakaz->klient_id = $klient_id;
        if($zakaz->load(Yii::$app->request->post()) && $zakaz->save()){
            Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Заказ успешно добавлен!');
            return $this->redirect(['/projects/index']);
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
            $oplata = new Oplata();
            $oplata->date_opl = date('Y-m-d');

            if($zakaz->load(Yii::$app->request->post()) && $zakaz->save()){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Изменения успешно сохранены!');
                return $this->redirect(['/projects/view', 'id' => $id]);
            }

            if($tiket->load(Yii::$app->request->post()) && $tiket->validate()){
                $tiket->saveTiket($id);
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Задача успешно добавлена!');
                return $this->redirect(['/projects/view', 'id' => $id]);
            }

            if($zakazFiles->load(Yii::$app->request->post()) && $zakazFiles->validate()) {
                $zakazFiles->uploadFile = UploadedFile::getInstance($zakazFiles, 'uploadFile');
                if ($zakazFiles->upload($zakaz->projectname, $id)) {
                    Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Файл успешно загружен!');
                    return $this->redirect(['/projects/view', 'id' => $id]);
                }
            }

            if($oplata->load(Yii::$app->request->post()) && $oplata->save()){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Оплата успешно добавлена!');
                return $this->redirect(['/projects/view', 'id' => $id]);
            }
            
            return $this->render('view', [
                'zakaz' => $zakaz,
                'tiket' => $tiket,
                'zakazFiles' => $zakazFiles,
                'oplata' => $oplata,


            ]);
        }else {
            return $this->redirect(['/projects/index']);
        }
    }

    public function actionClose(){
        $zakaz_id = Yii::$app->request->post('zakaz_id');
        if(!is_null($zakaz_id)){
            if(Zakaz::closeZakaz($zakaz_id)){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Проект выполнен!');
                return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
            }
        }
    }
    
}