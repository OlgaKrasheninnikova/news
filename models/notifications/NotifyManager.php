<?php
namespace app\models\notifications;
use app\models\News;
use app\models\User;


/**
 * К этому классу следует обращаться для оповещения пользователей
 *
 * Class NotifyManager
 * @package app\models
 */
class NotifyManager {

    /**
     * @param News $item
     */
    public function notifyAboutNewsItem(News $item) {
        $notifyMail = new Notificator( new Mailer() );
        $notifyMail->notifyAboutNewsItem($item);

        $notifyPush = new Notificator( new Pusher() );
        $notifyPush->notifyAboutNewsItem($item);
    }

}