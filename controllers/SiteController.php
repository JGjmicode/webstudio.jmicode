<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\LoginForm;
use app\models\RegForm;
use app\models\ContactForm;
use app\controllers\BehaviorsController;
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

    public function actionContact()
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




    public function actionUserprofile() {
        $user = User::find()->where(['id' => Yii::$app->user->getId()])->one();
        if($user->load(Yii::$app->request->post()) && $user->validate()){
            if($user->editProfile()){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Изменения успешно сохранены!');
                return $this->redirect('/site/userprofile');
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка!');
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
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Пользователь активирован!');
                return $this->redirect('/site/manage-profile');
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                return $this->redirect('/site/manage-profile');
            }
            
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр id');
            return $this->redirect('/site/manage-profile');
        }
    }

    public function actionDeactivateUser($id = null){
        if(!is_null($id)){
            if(User::deactivateUser($id)){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Пользователь деактивирован!');
                return $this->redirect('/site/manage-profile');
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                return $this->redirect('/site/manage-profile');
            }

        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр id');
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
