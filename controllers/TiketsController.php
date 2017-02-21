<?php
namespace app\controllers;
use app\controllers\BehaviorsController;
use app\models\Priority;
use app\models\Tiket;
use app\models\TiketSearch;
use Yii;
use yii\helpers\Url;

class TiketsController extends BehaviorsController{

    
    public function actionIndex(){
        $searchModel = new TiketSearch();
        //$searchModel->active = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id = NULL){
        if(!is_null($id)){
            $tiket = Tiket::findOne($id);
            return $this->render('view', [
                'tiket' => $tiket,
            ]);
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
    

    
    
}