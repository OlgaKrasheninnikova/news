<?php


namespace app\models;

use dektrium\user\Mailer;
use dektrium\user\models\SettingsForm as BaseSettingsForm;
use dektrium\user\Module;
use Yii;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends BaseSettingsForm
{
    /** @var boolean */
    public $notifications_email;

    /** @var boolean */
    public $notifications_push;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'            => Yii::t('user', 'Email'),
            'username'         => Yii::t('user', 'Имя пользователя'),
            'new_password'     => Yii::t('user', 'Новый пароль'),
            'current_password' => Yii::t('user', 'Текущий пароль'),
            'notifications_email' => Yii::t('user', 'Отправлять уведомления по email о появление новостей'),
            'notifications_push' => Yii::t('user', 'Отправлять push уведомления о появление новостей'),
        ];
    }

    public function __construct(Mailer $mailer, $config = [])
    {
        parent::__construct($mailer, $config);
        $this->setAttributes([
            'notifications_email' => $this->user->notifications_email,
            'notifications_push' => $this->user->notifications_push,
        ], false);
    }


    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->username = $this->username;
            $this->user->password = $this->new_password;
            $this->user->notifications_email = $this->notifications_email;
            $this->user->notifications_push = $this->notifications_push;
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } elseif ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }

            return $this->user->save();
        }

        return false;
    }

}