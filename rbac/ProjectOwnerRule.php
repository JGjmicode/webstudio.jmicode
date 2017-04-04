<?php
namespace app\rbac;

use yii\rbac\Rule;
use app\models\Zakaz;

/**
 * Проверяем UserID на соответствие с пользователем, переданным через параметры
 */
class ProjectOwnerRule extends Rule
{
    public $name = 'isProjectOwner';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['zakaz_id']) ? Zakaz::findOne($params['zakaz_id'])->create_by == $user : false;
    }
}
