<?php
namespace app\rbac;

use yii\rbac\Rule;
use app\models\ZakazRelate;
/**
* Проверяем UserID на соответствие с пользователем, переданным через параметры
*/
class RelateProjectUserRule extends Rule
{
public $name = 'isRelateProjectUser';

/**
* @param string|int $user the user ID.
* @param Item $item the role or permission that this rule is associated width.
* @param array $params parameters passed to ManagerInterface::checkAccess().
* @return bool a value indicating whether the rule permits the role or permission it is associated with.
*/
public function execute($user, $item, $params){
	$relate = ZakazRelate::find()->where(['zakaz_id' => $params['zakaz_id'], 'user_id' => $user])->all();
	if(!empty($relate)){
		return true;
	}else{
		return false;
	}

}
}