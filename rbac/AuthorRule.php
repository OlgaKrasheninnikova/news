<?php
namespace app\rbac;

use yii\rbac\Rule;

/**
 * Checks if userID matches user passed via params
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param int $user
     * @param $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        return  isset($params['news']) ? $params['news']->created_user_id == $user : false;
    }
}