<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // добавляем разрешение "createTiket"
        $createTiket = $auth->createPermission('createTiket');
        $createTiket->description = 'Create a Tiket';
        $auth->add($createTiket);

        // добавляем разрешение "viewTiket"
        $viewTiket = $auth->createPermission('viewTiket');
        $viewTiket->description = 'View a Tiket';
        $auth->add($viewTiket);

        // добавляем разрешение "closeTiket"
        $closeTiket = $auth->createPermission('closeTiket');
        $closeTiket->description = 'Close a Tiket';
        $auth->add($closeTiket);

        // добавляем разрешение "sendTchat"
        $sendTchat = $auth->createPermission('sendTchat');
        $sendTchat->description = 'Send message to tiket';
        $auth->add($sendTchat);

        // добавляем разрешение "createProject"
        $createProject = $auth->createPermission('createProject');
        $createProject->description = 'Create a Project';
        $auth->add($createProject);

        // добавляем разрешение "viewProject"
        $viewProject = $auth->createPermission('viewProject');
        $viewProject->description = 'View a Project';
        $auth->add($viewProject);

        // добавляем разрешение "editProject"
        $editProject = $auth->createPermission('editProject');
        $editProject->description = 'Edit a Project';
        $auth->add($editProject);

        // добавляем разрешение "closeProject"
        $closeProject = $auth->createPermission('closeProject');
        $closeProject->description = 'Close a Project';
        $auth->add($closeProject);

        // добавляем разрешение "createClient"
        $createClient = $auth->createPermission('createClient');
        $createClient->description = 'Create a Client';
        $auth->add($createClient);

        // добавляем разрешение "editClient"
        $editClient = $auth->createPermission('editClient');
        $editClient->description = 'Edit a Client';
        $auth->add($editClient);

        // добавляем разрешение "activateUser"
        $activateUser = $auth->createPermission('activateUser');
        $activateUser->description = 'Activate a User';
        $auth->add($activateUser);

        // добавляем разрешение "deactivateUser"
        $deactivateUser = $auth->createPermission('deactivateUser');
        $deactivateUser->description = 'Deactivate a User';
        $auth->add($deactivateUser);

        // добавляем роль "admin" и присваиваем все разрешения
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createTiket);
        $auth->addChild($admin, $viewTiket);
        $auth->addChild($admin, $closeTiket);
        $auth->addChild($admin, $sendTchat);
        $auth->addChild($admin, $createProject);
        $auth->addChild($admin, $viewProject);
        $auth->addChild($admin, $editProject);
        $auth->addChild($admin, $closeProject);
        $auth->addChild($admin, $createClient);
        $auth->addChild($admin, $editClient);
        $auth->addChild($admin, $activateUser);
        $auth->addChild($admin, $deactivateUser);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($admin, 7);

        //Добавление правила проверки имеет ли отношение пользователь к проекту
        $rule = new \app\rbac\RelateProjectUserRule;
        $auth->add($rule);

        // добавляем разрешение "createOwnTiket"
        $createOwnTiket = $auth->createPermission('createOwnTiket');
        $createOwnTiket->description = 'Create a Tiket by related user';
        $createOwnTiket->ruleName = $rule->name;
        $auth->add($createOwnTiket);
        $auth->addChild($createOwnTiket, $createTiket);

        // добавляем разрешение "viewOwnTiket"
        $viewOwnTiket = $auth->createPermission('viewOwnTiket');
        $viewOwnTiket->description = 'View a Tiket by related user';
        $viewOwnTiket->ruleName = $rule->name;
        $auth->add($viewOwnTiket);
        $auth->addChild($viewOwnTiket, $viewTiket);

        // добавляем разрешение "sendOwnTchat"
        $sendOwnTchat = $auth->createPermission('sendOwnTchat');
        $sendOwnTchat->description = 'Send message to tiket by related user';
        $sendOwnTchat->ruleName = $rule->name;
        $auth->add($sendOwnTchat);
        $auth->addChild($sendOwnTchat, $sendTchat);

        // добавляем разрешение "viewOwnProject"
        $viewOwnProject = $auth->createPermission('viewOwnProject');
        $viewOwnProject->description = 'View project by related user';
        $viewOwnProject->ruleName = $rule->name;
        $auth->add($viewOwnProject);
        $auth->addChild($viewOwnProject, $viewProject);

        //Добавление правила проверки что пользователь является менеджером по данному проекту или исполнителем данного тикета
        $closeOwnTiketRule = new \app\rbac\TiketManagerOrPerformerRule;
        $auth->add($closeOwnTiketRule);

        // добавляем разрешение "closeOwnTiket"
        $closeOwnTiket = $auth->createPermission('closeOwnTiket');
        $closeOwnTiket->description = 'Close tiket by related user';
        $closeOwnTiket->ruleName = $closeOwnTiketRule->name;
        $auth->add($closeOwnTiket);
        $auth->addChild($closeOwnTiket, $closeTiket);

        //Добавление правила проверки что пользователь является создателем данного проекта
        $projectOwnerRule = new \app\rbac\ProjectOwnerRule;
        $auth->add($projectOwnerRule);

        // добавляем разрешение "editOwnProject"
        $editOwnProject = $auth->createPermission('editOwnProject');
        $editOwnProject->description = 'Edit project by Owner';
        $editOwnProject->ruleName = $projectOwnerRule->name;
        $auth->add($editOwnProject);
        $auth->addChild($editOwnProject, $editProject);

        // добавляем разрешение "closeOwnProject"
        $closeOwnProject = $auth->createPermission('closeOwnProject');
        $closeOwnProject->description = 'Close project by Owner';
        $closeOwnProject->ruleName = $projectOwnerRule->name;
        $auth->add($closeOwnProject);
        $auth->addChild($closeOwnProject, $closeProject);

        //Добавление правила проверки что пользователь является создателем данного клиента
        $clientOwnerRule = new \app\rbac\ClientOwnerRule;
        $auth->add($clientOwnerRule);

        // добавляем разрешение "editOwnClient"
        $editOwnClient = $auth->createPermission('editOwnClient');
        $editOwnClient->description = 'Edit client by Owner';
        $editOwnClient->ruleName = $clientOwnerRule->name;
        $auth->add($editOwnClient);
        $auth->addChild($editOwnClient, $editClient);

    }
}