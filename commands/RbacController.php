<?php

namespace app\commands;
use app\models\User;
use app\models\UserManager;


/**
 * Class RbacController. Назначает rbca роли пользоателям.
 *
 * @package app\commands
 */
class RbacController extends  \yii\console\Controller
{

    /**
     * @param string $userId
     */
    public function actionMakeAdmin($userId)
    {
        echo $this->giveRole($userId, UserManager::ROLE_ADMIN);
    }

    /**
     * @param string $userId
     */
    public function actionMakeManager($userId)
    {
        echo $this->giveRole($userId, UserManager::ROLE_MANAGER);
    }


    /**
     * @param string $userId
     * @param string $role
     * @return string
     */
    private function giveRole($userId, $role) {
        try {
            $user = User::findOne(['id' => $userId]);
            if (!$user) {
                $user = User::findOne(['username' => $userId]);
            }
            if (!$user) {
                $user = User::findOne(['email' => $userId]);
            }
            if (!$user) {
                return "Пользователь '{$userId}' не найден.\n";
            }
            $id = $user->getId();
            $auth = \Yii::$app->authManager;
            $hasRole = $auth->checkAccess($id, $role);
            if ($hasRole) {
                return "Пользователь {$user->getName()} уже имеет права $role \n";
            }
            $adminRole = $auth->getRole($role);
            $auth->assign($adminRole, $id);
        } catch (\Exception $e) {
            return "Не удалось назначить пользователю роль $role. Причина: " . $e->getMessage(). "\n";
        }
        return "Пользователю {$user->getName()} была успешно назначен роль $role \n";
    }



}
