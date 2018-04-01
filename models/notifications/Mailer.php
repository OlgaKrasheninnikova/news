<?php

namespace app\models\notifications;

use app\helpers\Config;
use app\models\News;
use app\models\User;
use app\models\UserManager;

/**
 * Class Mailer. Для оповещений по email.
 *
 * @package app\models\notifications
 */
class Mailer extends NotifyMethod
{

    /**
     * @var \yii\mail\MailerInterface
     */
    private $mailerComponent;

    /**
     * @var string
     */
    private $viewPath = '@app/views/mail';


    public function __construct()
    {
        $this->mailerComponent = \Yii::$app->mailer;
        $this->mailerComponent->viewPath = $this->viewPath;
    }

    /**
     * @return array
     */
    public function getUsersListToNotifyAboutNewsItem() {
        return UserManager::getListToNotifyByEmail();
    }

    /**
     * Отправляет пользователю письмо о том, что появилась новость
     *
     * @param User $user
     * @param News $item
     */
    public function notifyAboutNewsItem(User $user, News $item) {
        $subject = Config::getInstance()->getParam('newsItemLetterSubject', 'notifications', 'Новая новость');
        $this->sendMessage($user->getEmail(), $subject, 'news_notify',
            [
                'userName' => $user->getName(),
                'name' => $item->getName(),
                'text' => $item->getFullText(),
                'image' => $item->getImageSrc(),
            ]);
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     * @return bool
     */
    private function sendMessage($to, $subject, $view, $params = [])
    {
        $adminEmail = Config::getInstance()->getParam('adminEmail', 'notifications', 'admin@admin.ru') ;
        return $this->mailerComponent->compose(['html' => $view, 'text' => $view], $params)
            ->setTo($to)
            ->setFrom( $adminEmail )
            ->setSubject($subject)
            ->send();
    }
}