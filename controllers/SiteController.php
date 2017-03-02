<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegForm;
use app\models\ContactForm;
use app\models\Zakaz;
use app\models\Klient;
use app\models\Tiket;
use app\controllers\BehaviorsController;
use yii\web\UploadedFile;
use yii\helpers\Url;
use app\models\UsersSearch;
use kartik\alert\Alert;

class SiteController extends BehaviorsController
{

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

    public function actionIndex()
    {        
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            User::setLastLogin();
            return $this->goHome();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }    

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    public function actionRegister(){
        
        $model = new RegForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if(!is_null($model->reg())){
                    return $this->redirect('/site/login');
                }

        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }    

    /*public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
// *************************************************************    
    public function actionZakaz() {        
        $zakaz = new Zakaz;      
        $data = $zakaz->getTab1();
        
        return $this->render("zakaz", array("data"=>$data));        
    }
    
    public function actionEmptyz() {                
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $zakaz = new Zakaz;
            $id = $zakaz->setNewZakaz($param);
            
            $data = $zakaz->getZakaz($id);
            return $this->render("ezakaz", ["data" => $data, "ss" => 0]);
        }
        
        if (Yii::$app->request->get("id"))
            return $this->render("emptyz", ["id" => Yii::$app->request->get("id")]);
        else
            return $this->render("emptyz", ["id" => 0]);
    }
    
    public function actionEmptyk() {        
        if (Yii::$app->request->post()){

            $param = Yii::$app->request->post();
            $klient = new Klient;
            $id = $klient->setNewKlient($param);
            
            $data = $klient->getKlient($id);
            return $this->render("eklient", ["data" => $data]);
        }
        
        return $this->render("emptyk");
    }    
    */
    public function actionKlient() {        
        $klient = new Klient;                     
        
        $data = $klient->find()->all();
        
        return $this->render("klient", array("data"=>$data));        
    }    
    /*
    public function actionEzakaz() {        
        $id = Yii::$app->request->get("id");        
        $model = new Zakaz;
        $zakaz = Zakaz::findOne($id);

        if (Yii::$app->request->post("sum")) {
            $sum = Yii::$app->request->post("sum");
            $date = Yii::$app->request->post("date_pay");
            $prim = Yii::$app->request->post("prim1");
            $model->setZakazPay($sum, $date, $prim, $id);        
        }
        if (Yii::$app->request->post("title")) {
            $title = Yii::$app->request->post("title");
            $text = Yii::$app->request->post("text");
            $model->setTiket($title, $text, $id);
        }
        if (Yii::$app->request->post('file-des')) {
            $desc = Yii::$app->request->post('file-des');
            $file = UploadedFile::getInstanceByName('upFile');
            $model->uploadFiles($file, $desc, $id);
        }
        
        $data = $model->getZakaz($id);

        if($zakaz->load(Yii::$app->request->post()) && $zakaz->save()){
            return $this->render("ezakaz", ["data" => $data, "ss" => 0, 'model' => $zakaz]);
        }
        
            return $this->render("ezakaz", ["data" => $data, "ss" => 0, 'model' => $zakaz]);
    }
    */
    public function actionEklient() {         
        $id = Yii::$app->request->get("id");        
        $model = new Klient;

        if (Yii::$app->request->post("name")) {
            $name = Yii::$app->request->post("name");
            $phone = Yii::$app->request->post("phone");
            $mail = Yii::$app->request->post("mail");
            $skype = Yii::$app->request->post("skype");
            $des = Yii::$app->request->post("des");
            
            $model->setContact($name, $phone, $mail, $skype, $des, $id);
        }        
        
        $data = $model->getKlient($id);         
        
        return $this->render("eklient", ["data" => $data]);        
    }
/*
    public function actionTiket(){            
        
        $id = Yii::$app->request->get("id");
        $model = new Tiket;                                
        
        if (Yii::$app->request->post("text")) {
            $model->setTiketText(Yii::$app->request->post("text"), $id);            
        }        
        
        $data = $model->getTiket($id);
        
            return $this->render("tiket", ["data"=>$data]);
    }
    */
    public function actionUserprofile() {
        $user = User::find()->where(['id' => Yii::$app->user->getId()])->one();
        if($user->load(Yii::$app->request->post()) && $user->validate()){
            if($user->editProfile()){
                return $this->redirect('/site/userprofile');
            }
        }

        return $this->render('userprofile', [
            'user' => $user,
        ]);
    }

    public function actionManageProfile(){
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('manage-index', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionActivateUser($id = null){
        if(!is_null($id)){
            if(User::activateUser($id)){
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Пользователь активирован!');
                return $this->redirect('/site/manage-profile');
            }else{
                Yii::$app->session->setFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                return $this->redirect('/site/manage-profile');
            }
            
        }else{
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр id');
            return $this->redirect('/site/manage-profile');
        }
    }

    public function actionDeactivateUser($id = null){
        if(!is_null($id)){
            if(User::deactivateUser($id)){
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Пользователь деактивирован!');
                return $this->redirect('/site/manage-profile');
            }else{
                Yii::$app->session->setFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                return $this->redirect('/site/manage-profile');
            }

        }else{
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр id');
            return $this->redirect('/site/manage-profile');
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    }

    
}
