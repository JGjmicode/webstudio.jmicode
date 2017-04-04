<?php
namespace app\controllers;
use app\controllers\BehaviorsController;
use app\models\Priority;
use app\models\Tchat;
use app\models\Tiket;
use app\models\TiketSearch;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use kartik\alert\Alert;
class TiketsController extends BehaviorsController{


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
        $searchModel = new TiketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $tiket = new Tiket();
        if($tiket->load(Yii::$app->request->post()) && $tiket->validate()){
            if(Yii::$app->user->can('createTiket', ['zakaz_id' => $tiket->zakaz_id])) {
                if ($tiket->saveTiket($tiket->zakaz_id)) {
                    Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Задача успешно добавлена!');
                    return $this->redirect('/tikets/index');
                } else {
                    Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                    return $this->redirect('/tikets/index');
                }
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на добавление задачи!');
                return $this->redirect('/tikets/index');
            }
        }
        
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tiket' => $tiket,

        ]);
    }

    public function actionView($id = NULL){
        if(!is_null($id)){
            $tiket = Tiket::findOne($id);
            $tchat = new Tchat();
            if(!Yii::$app->user->can('viewTiket', ['zakaz_id' => $tiket->zakaz_id])) {
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на просмотр задачи!');
                return $this->redirect('/tikets/index');
            }
            if(!$tiket->status && $tiket->performer_id == Yii::$app->user->getId()){
                $tiket->setViewed();
            }
            if($tchat->load(Yii::$app->request->post()) && $tchat->validate()) {
                $tchat->uploadFile = UploadedFile::getInstance($tchat, 'uploadFile');
                if(is_null($tchat->uploadFile)){
                    if($tchat->saveMessage($id)){
                        Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Сообщение успешно добавлено!');
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }else{
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }
                }else{
                    if($tchat->upload($tiket->zakaz->projectname, $id)){
                        Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Сообщение успешно добавлено!');
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }else{
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }
                    
                }
            }
            return $this->render('view', [
                'tiket' => $tiket,
                'tchat' => $tchat,
            ]);
        }else{
            return $this->redirect('/tikets/index');
        }
    }

    public function actionCloseTiket($id = NULL){
        if(!is_null($id)){
            if(!Yii::$app->user->can('closeTiket', ['tiket_id' => $id])) {
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Нет прав на закрытие задачи!');
                return $this->redirect(['/tikets/view', 'id' => $id]);
            }
            $zakazId = Tiket::closeTiket($id);
            if($zakazId){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Задача # '.$id.' выполнена!' );
                return $this->redirect(Url::to(['/projects/view', 'id' => $zakazId]));
            }
        }
    }

    public function actionDownload($path)
    {
        return Yii::$app->response->sendFile(Yii::$app->basePath.'/web'.$path);
    }
    

    
    
}