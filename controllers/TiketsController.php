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
class TiketsController extends BehaviorsController{

    public $session;

    public function actions()
    {
        $this->session = Yii::$app->session;
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
        $success = NULL;
        if($tiket->load(Yii::$app->request->post()) && $tiket->validate()){
            if($tiket->saveTiket($tiket->zakaz_id)){
                $success = 'Задача успешно добавлена!';
            }

        }
        
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tiket' => $tiket,
            'success' => $success,
        ]);
    }

    public function actionView($id = NULL){
        if(!is_null($id)){
            $tiket = Tiket::findOne($id);
            $tchat = new Tchat();
            if($tchat->load(Yii::$app->request->post()) && $tchat->validate()) {
                $tchat->uploadFile = UploadedFile::getInstance($tchat, 'uploadFile');
                if(is_null($tchat->uploadFile)){
                    if($tchat->saveMessage($id)){
                        $this->session->setFlash('message', 'Сообщение успешно добавлено!');
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }else{
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }
                }else{
                    if($tchat->upload($tiket->zakaz->projectname, $id)){
                        $this->session->setFlash('message', 'Сообщение успешно добавлено!');
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }else{
                        return $this->redirect(['/tikets/view', 'id' => $id]);
                    }
                    
                }
            }
            return $this->render('view', [
                'tiket' => $tiket,
                'tchat' => $tchat,
                'session' => $this->session,
            ]);
        }else{
            return $this->redirect('/tikets/index');
        }
    }

    public function actionCloseTiket($id = NULL){
        if(!is_null($id)){
            $zakazId = Tiket::closeTiket($id);
            if($zakazId){
                return $this->redirect(Url::to(['/projects/view', 'id' => $zakazId]));
            }
        }
    }

    public function actionDownload($path)
    {
        return Yii::$app->response->sendFile(Yii::$app->basePath.'/web'.$path);
    }
    

    
    
}