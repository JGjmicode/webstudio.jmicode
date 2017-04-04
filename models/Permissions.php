<?php
namespace app\models;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use app\models\User;
use kartik\alert\Alert;
class Permissions extends Model{

    public $permissions;
    public $allPermissions;
    public $role;
    public $auth;
    public $roles;
    public $newRole;
    public $newRoleDescription;
    public $action;
    public $userRoles;

    public function __construct($config =[])
    {
        parent::__construct($config);
        $this->auth = Yii::$app->authManager;
    }

    public function rules()
    {
        return [
            [['permissions', 'action', 'newRoleDescription'], 'safe'],
            ['newRole', 'required', 'when' => function ($model) {
                return $model->action == 'newRole';
            }, 'whenClient' => "function (attribute, value) {
                return $('#permissions-action').val() == 'newRole';
                }"],
        ];
    }

    public function attributeLabels()
    {
        return [
            'newRole' => 'Название',
            'newRoleDescription' => 'Описание',
        ];
    }

    public function getSelectPermissions($role){
        $rolePermissions = $this->auth->getChildren($role);
        $permissions = array();
        foreach ($rolePermissions as $key => $value){
            $permissions[] = $key;
        }
        return $permissions;
    }

    public function getAllPermissions(){
        $permissions = $this->auth->getPermissions();
        $this->allPermissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->allPermissions;
    }

    public function getPermissions($role){
        $this->role = $role;
        $this->permissions = $this->getSelectPermissions($this->role);
        $this->allPermissions = $this->getAllPermissions();
    }
    
    public function updatePermissions(){

        if(is_null($this->auth->getRole($this->role))){
            return false;
        }
        $oldPermissions = $this->getSelectPermissions($this->role);
        foreach ($oldPermissions as $permission){

            if(!is_array($this->permissions)){
                $this->permissions = array();
            }
            if(!ArrayHelper::isIn($permission, $this->permissions)){
                $this->auth->removeChild($this->auth->getRole($this->role), $this->auth->getPermission($permission));
            }
        }
        foreach ($this->permissions as $permission){
            if(!ArrayHelper::isIn($permission, $oldPermissions)){
                $this->auth->addChild($this->auth->getRole($this->role), $this->auth->getPermission($permission));
            }
        }

        return true;

    }

    public function getRoles($id= null){
        if(is_null($id)) {
            $this->roles = $this->auth->getRoles();
        }else{
            $roles = $this->auth->getRoles();
            foreach ($roles as $role=>$value){
                foreach ($this->auth->getRolesByUser($id) as $userRole=>$value){
                    if($role == $userRole){
                        unset($roles[$role]);
                    }
                }
            }
            $this->roles = $roles;
        }
    }

    public function addNewRole(){
        $role = $this->auth->createRole($this->newRole);
        $role->description = $this->newRoleDescription;
        $this->auth->add($role);
    }

    public function getUserRoles($id){
        $this->userRoles = $this->auth->getRolesByUser($id);
    }

    public function setRole($id, $role){
        $user = User::findOne($id);
        if(is_null($user)){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Пользователь не найден!');
            return false;
        }
        if(is_null($this->auth->getRole($role))){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Роль не существует!');
            return false;
        }
        if(is_null($this->auth->getAssignment($role, $id))) {
            $this->auth->assign($this->auth->getRole($role), $id);
            return true;
        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Роль уже добавлена!');
            return false;
        }
    }

    public function delRole($id, $role){
        $user = User::findOne($id);
        if(is_null($user)){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Пользователь не найден!');
            return false;
        }
        if(is_null($this->auth->getRole($role))){
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'Роль не существует!');
            return false;
        }
        if(!is_null($this->auth->getAssignment($role, $id))) {
            return $this->auth->revoke($this->auth->getRole($role), $id);

        }else{
            Yii::$app->session->addFlash(Alert::TYPE_DANGER, 'У пользователя отсутствует данная роль!');
            return false;
        }
    }

    
}