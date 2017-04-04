<?php
namespace app\rbac;

use yii\rbac\Rule;
use app\models\Klient;

/**
 * Проверяем UserID на соответствие с пользователем, переданным через параметры
 */
class ClientOwnerRule extends Rule
{
    public $name = 'isClientOwner';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['id']) ? Klient::findOne($params['id'])->create_by == $user : false;
    }
}
