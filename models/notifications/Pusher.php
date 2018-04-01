<?php

namespace app\models\notifications;

use app\models\News;
use app\models\User;

/**
 * Class Pusher. Для push опоавещений в браузере
 *
 * @package app\models\notifications
 */
class Pusher extends NotifyMethod
{
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getUsersListToNotifyAboutNewsItem() {
        return User::getListToNotifyByPush();
    }


    /**
     * Отправляет пользователю push о том, что появилась новость
     *
     * @param User $user
     * @param News $item
     */
    public function notifyAboutNewsItem(User $user, News $item) {

        echo 'pusher works ' . $user->getEmail() . '----' . $item->getName();

    }

}