<?php
namespace app\controllers;
use app\controllers\BehaviorsController;
use app\models\Kontakt;
use app\models\Tiket;
use app\models\Zakaz;
use app\models\ZakazRelate;
use app\models\ZakazSearch;
use Yii;
use yii\helpers\Url;
use app\models\Zakazfiles;
use yii\web\UploadedFile;
use app\models\Oplata;
use kartik\alert\Alert;
use app\models\User;
use yii\db\Query;
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
        if(!Yii::$app->user->can('createProject')){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на создание проекта!');
            return $this->redirect(['/projects/index']);
        }
        $zakaz = new Zakaz();
        $zakaz->klient_id = $klient_id;
        $zakaz->date_start = date("Y-m-d");
        if ($zakaz->load(Yii::$app->request->post()) && $zakaz->addProject()) {
            Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Заказ успешно добавлен!');
            return $this->redirect(['/projects/index']);
        }
        return $this->render('add', [
            'zakaz' => $zakaz,
        ]);
    }

    public function actionView($id = NULL){
        if(!is_null($id)) {
            $zakaz = Zakaz::find()->where(['zakaz.id' => $id])->joinWith('relatedUsers')->one();
            $tiket = new Tiket();
            $tiket->zakaz_id = $id;
            $zakazFiles = new Zakazfiles();
            $oplata = new Oplata();
            $oplata->date_opl = date('Y-m-d');
            $zakazRelate = new ZakazRelate();

            if(Yii::$app->user->can('editProject', ['zakaz_id' => $zakaz->id])) {
                $editProject = true;
            }else{
                $editProject = false;

            }

            if(!Yii::$app->user->can('viewProject', ['zakaz_id' => $zakaz->id])) {
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на просмотр проекта!');
                return $this->redirect(['/projects/index']);
            }

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
                'editProject' => $editProject,
                'zakazRelate' => $zakazRelate,


            ]);
        }else {
            return $this->redirect(['/projects/index']);
        }
    }

    public function actionClose(){
        $zakaz_id = Yii::$app->request->post('zakaz_id');
        if(!is_null($zakaz_id)){
            if(Yii::$app->user->can('closeProject', ['zakaz_id' => $zakaz_id])) {
                if (Zakaz::closeZakaz($zakaz_id)) {
                    Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Проект выполнен!');
                    return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
                }
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на закрытие проекта!');
                return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
            }
        }
    }

    public function actionGetUsers($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('users')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->name];
        }
        return $out;
    }

    public function actionAddRelatedUser(){
        $related = new ZakazRelate();
        if($related->load(Yii::$app->request->post())){
            if(Yii::$app->user->can('editProject', ['zakaz_id' => $related->zakaz_id])) {
                if($related->addRelatedUser()){
                    Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Пользователь добавлен к проекту!');
                    return $this->redirect(['/projects/view', 'id' => $related->zakaz_id]);
                }else{
                    Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Пользователь уже добавлен!');
                    return $this->redirect(['/projects/view', 'id' => $related->zakaz_id]);
                }
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на добавление пользователя!');
                return $this->redirect(['/projects/view', 'id' => $related->zakaz_id]);
            }
        }
        return $this->redirect(['/projects/index']);
    }

    public function actionRemoveRelatedUser($user_id = NULL, $zakaz_id = NULL){
        if(!is_null($user_id) && !is_null($zakaz_id)){
            if(Yii::$app->user->can('editProject', ['zakaz_id' => $zakaz_id])) {
                if(ZakazRelate::removeRelatedUser($user_id, $zakaz_id)){
                    Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Пользователь откреплен от проекта!');
                    return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
                }else{
                    Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка!');
                    return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
                }
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на открепление пользователя!');
                return $this->redirect(['/projects/view', 'id' => $zakaz_id]);
            }
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
            return $this->redirect(['/projects/index']);
        }

    }
    
}