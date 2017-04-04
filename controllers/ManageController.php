<?php
namespace app\controllers;

use app\models\Permissions;
use app\models\User;
use Yii;
use app\models\UsersSearch;
use kartik\alert\Alert;

class ManageController extends BehaviorsController{

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

    public function actionManageProfile(){
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $model = new Permissions();
        $model->getRoles();
        $model->getAllPermissions();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->addNewRole();
            Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Роль добавлена!');
            return $this->redirect('/manage/manage-profile');
        }
        return $this->render('manage-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionUserView($id = null){
        if(!is_null($id) && !is_null($user = User::findOne($id))) {
            $model = new Permissions();
            $model->getRoles($id);
            $model->getUserRoles($id);
            if ($user->load(Yii::$app->request->post()) && $user->validate()) {
                if ($user->editUserProfile()) {
                    Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Изменения успешно сохранены!');
                    return $this->redirect(['/manage/user-view', 'id' => $id]);
                } else {
                    Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка!');
                    return $this->redirect(['/manage/user-view', 'id' => $id]);
                }
            }
            return $this->render('user-view', [
                'user' => $user,
                'model' => $model,
            ]);
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Пользователь не найден!');
            return $this->redirect('/manage/manage-profile');
        }
    }

    public function actionActivateUser($id = null){
        if(!is_null($id)){
            if(User::activateUser($id)){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Пользователь активирован!');
                return $this->redirect('/manage/manage-profile');
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                return $this->redirect('/manage/manage-profile');
            }

        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр id');
            return $this->redirect('/manage/manage-profile');
        }
    }

    public function actionDeactivateUser($id = null){
        if(!is_null($id)){
            if(User::deactivateUser($id)){
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Пользователь деактивирован!');
                return $this->redirect('/manage/manage-profile');
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка');
                return $this->redirect('/manage/manage-profile');
            }

        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр id');
            return $this->redirect('/manage/manage-profile');
        }
    }

    public function actionAccessManager($role = null){
        if(is_null($role)){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Отсутствует обязательный параметр role');
            return $this->redirect('/manage/manage-profile');
        }
        $model = new Permissions();
        $model->getPermissions($role);
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermissions()) {
                Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Разрешения сохранены!');
                return $this->redirect('/manage/manage-profile');
            }else{
                Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Ошибка! Роль не существует!');
                return $this->redirect('/manage/manage-profile');
            }
        }
        return $this->render('access-manager', [

            'model' => $model,
        ]);
    }

    public function actionAddRole($id = null, $role = null){
        if(is_null($id) || is_null($role)){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Ошибка! Отсутствуют обязательные параметры!');
            return $this->redirect('/manage/manage-profile');
        }
        $model = new Permissions();
        if($model->setRole($id, $role)){
            Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Роль добавлена!');
            return $this->redirect(['/manage/user-view', 'id' => $id]);
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка!');
            return $this->redirect(['/manage/manage-profile']);
        }
    }

    public function actionDelRole($id = null, $role = null){
        if(is_null($id) || is_null($role)){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Ошибка! Отсутствуют обязательные параметры!');
            return $this->redirect('/manage/manage-profile');
        }
        $model = new Permissions();
        if($model->delRole($id, $role)){
            Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Роль удалена!');
            return $this->redirect(['/manage/user-view', 'id' => $id]);
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Произошла ошибка!');
            return $this->redirect(['/manage/manage-profile']);
        }
    }

}