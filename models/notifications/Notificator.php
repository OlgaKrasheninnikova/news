<?php
namespace app\models\notifications;
use app\models\News;
use app\models\User;


/**
 * Класс для оповещения пользователей
 *
 * Class Notificator
 * @package app\models
 */
class Notificator {

    /** @var notifyMethod  */
    private $notifyMethod;


    public function __construct(NotifyMethod $notifyMethod)
    {
        $this->notifyMethod = $notifyMethod;
    }

    /**
     * Оповещаем о добавленной новости всеми необходимыми способами
     *
     * @param News $item
     */
    public function notifyAboutNewsItem(News $item) {
        $users = $this->notifyMethod->getUsersListToNotifyAboutNewsItem();
        foreach ($users as $user) {
            /** User $user */
            $this->notifyMethod->notifyAboutNewsItem($user, $item);
        }

    }

}