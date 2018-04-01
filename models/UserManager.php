<?php

namespace app\models;

use dektrium\user\helpers\Password;
use dektrium\user\models\Token;
use dektrium\user\models\User as BaseUser;

class UserManager extends BaseUser
{

    public $is_manager;


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['create'][]   = 'notifications_email';
        $scenarios['update'][]   = 'notifications_email';
        $scenarios['register'][] = 'notifications_email';
        $scenarios['create'][]   = 'notifications_push';
        $scenarios['update'][]   = 'notifications_push';
        $scenarios['register'][] = 'notifications_push';
        return $scenarios;
    }

    /**
     * Переопределяем для изменения логики создания пользоателя из админки
     * Создаем НЕ активированного, в письмо включаем ссылку для активации
     *
     * @return bool
     */
    public function create()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $transaction = $this->getDb()->beginTransaction();

        try {
            //временный пароль понадобится для смены в ЛК
            $this->password = $this->password == null ? Password::generate(8) : $this->password;

            $this->trigger(self::BEFORE_CREATE);

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            if ($this->module->enableConfirmation) {
                /** @var Token $token */
                $token = \Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
                $token->link('user', $this);
            }

            $token = new Token();
            $this->mailer->sendWelcomeMessage($this, isset($token) ? $token : null);
            $this->trigger(self::AFTER_CREATE);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::warning($e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListToNotifyByEmail() {
        return self::find()->where(['notifications_email' => true])->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListToNotifyByPush() {
        return self::find()->where(['notifications_push' => true])->all();
    }
}
