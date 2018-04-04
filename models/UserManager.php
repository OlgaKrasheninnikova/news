<?php

namespace app\models;


class UserManager
{

    const ROLE_ADMIN = 'admin';

    const ROLE_MANAGER = 'manager';

    const PERMISSION_VIEW_NEWS = 'viewNews';
    const PERMISSION_CREATE_NEWS = 'createNews';
    const PERMISSION_UPDATE_NEWS = 'updateNews';
    const PERMISSION_DELETE_NEWS = 'deleteNews';

    const PERMISSION_UPDATE_OWN_NEWS = 'updateOwnNews';
    const PERMISSION_DELETE_OWN_NEWS = 'deleteOwnNews';


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
        return User::find()->where(['notifications_email' => true])->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListToNotifyByPush() {
        return User::find()->where(['notifications_push' => true])->all();
    }
}
