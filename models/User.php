<?php

namespace app\models;


use dektrium\user\models\User as BaseUser;
use dektrium\user\helpers\Password;
use dektrium\user\models\Token;


class User extends BaseUser
{


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
                $token = \Yii::createObject(['class' => Token::class, 'type' => Token::TYPE_CONFIRMATION]);
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
