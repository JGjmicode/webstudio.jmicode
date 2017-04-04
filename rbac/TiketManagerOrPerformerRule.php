<?php
namespace app\rbac;

use app\models\Tiket;
use yii\rbac\Rule;

/**
 * Проверяем UserID на соответствие с пользователем, переданным через параметры
 */
class TiketManagerOrPerformerRule extends Rule
{
    public $name = 'isTiketManagerOrPerformer';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $tiket = Tiket::findOne($params['tiket_id']);
        if(is_null($tiket)){
            return false;
        }
        if($user == $tiket->user_id  || $user == $tiket->performer_id){
            return true;
        }else{
            return false;
        }
    }
}
