<?php
namespace app\controllers;

use app\controllers\BehaviorsController;
use app\models\Klient;
use app\models\Kontakt;
use Yii;
use kartik\alert\Alert;

class ClientController extends BehaviorsController{

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
        $clients = Klient::find()->all();
        return $this->render('index', [
            'clients' => $clients
        ]);
    }

    public function actionAdd(){
        $client = new Klient();
        if($client->load(Yii::$app->request->post()) && $client->save()){
            Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Клиент успешно добавлен!');
            return $this->redirect(['/client/view', 'id' => $client->id]);
        }
        return $this->render('add', [
            'client' => $client
        ]);
    }

    public function actionView($id = null){
        if(!is_null($id)){
            $client = Klient::findOne($id);
            $contact = new Kontakt();

            if($client->load(Yii::$app->request->post()) && $client->save()){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Изменения сохранены!');
                return $this->redirect(['/client/view', 'id' => $id]);
            }
            if($contact->load(Yii::$app->request->post()) && $contact->save()){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Контакт успешно добавлен!');
                return $this->redirect(['/client/view', 'id' => $id]);
            }

            return $this->render('view', [
                'client' => $client,
                'contact' => $contact,
            ]);
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Клиент не найден!');
            return $this->redirect('/client/index');
        }
    }

    public function actionGetContact($contact_id = NULL){
        if(!is_null($contact_id)){
            if($contact = Kontakt::getContact($contact_id)){
                return json_encode($contact);
            }else {
                return false;
            }
        }else {
            return false;
        }
    }
    public function actionDeleteContact($contact_id = NULL, $client_id = NULL){
        if(!is_null($contact_id) && !is_null($client_id)){
            if(Kontakt::deleteContact($contact_id)){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Контакт успешно удален!');
                return $this->redirect(['/client/view', 'id' => $client_id]);
            }else {
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка!');
                return $this->redirect(['/client/view', 'id' => $client_id]);
            }
        }else {
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр!');
            return $this->redirect(['/client/index']);
        }
    }

}