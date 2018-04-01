<?php

namespace app\models\notifications;

use app\models\News;
use app\models\User;

/**
 * Class Mailer. Для оповещений по email.
 *
 * @package app\models\notifications
 */
class Mailer extends NotifyMethod
{

    private $mailerComponent;

    private $viewPath = '@app/views/mail';

    private $senderEmail = 'news-site@site.com';

    private $newsItemSubject = 'На сайте появилась новость';

    public function __construct()
    {
        $this->mailerComponent = \Yii::$app->mailer;
        $this->mailerComponent->viewPath = $this->viewPath;
    }

    /**
     * @return array
     */
    public function getUsersListToNotifyAboutNewsItem() {
        return User::getListToNotifyByEmail();
    }

    /**
     * Отправляет пользователю письмо о том, что появилась новость
     *
     * @param User $user
     * @param News $item
     */
    public function notifyAboutNewsItem(User $user, News $item) {
        $this->sendMessage($user->getEmail(), $this->newsItemSubject, 'news_notify',
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
        $mailer = $this->mailerComponent;
        //$mailer->getView()->theme = Yii::$app->view->theme;

        return $mailer->compose(['html' => $view, 'text' => $view], $params)
            ->setTo($to)
            ->setFrom($this->senderEmail)
            ->setSubject($subject)
            ->send();
    }
}