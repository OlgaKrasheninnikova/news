<?php
namespace app\models\notifications;


use app\models\News;
use app\models\User;

abstract class NotifyMethod {

    /**
     * Оповещает пользователя о том, что появилась новость
     *
     * @param User $user
     * @param News $item
     * @return mixed
     */
    abstract public function notifyAboutNewsItem(User $user, News $item);

    /**
     * @return mixed
     */
    abstract public function getUsersListToNotifyAboutNewsItem();

}