<?php
namespace app\models\notifications;


use app\models\News;
use app\models\User;

/**
 * Class NotifyMethod - абстрактный для методов оповещения пользователей
 *
 * @package app\models\notifications
 */
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
     * Получает список пользователей, которых необходимо оповестить о появление новости
     *
     * @return mixed
     */
    abstract public function getUsersListToNotifyAboutNewsItem();

}