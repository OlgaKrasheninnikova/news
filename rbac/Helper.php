<?php

namespace app\rbac;


use yii\web\User;

class Helper {

    /**
     * @param $news
     * @param User $user
     * @return array
     */
    static function getPermissionsForNews($news, User $user) {
        $permissions = [];
        foreach ($news as $item) {
            /** News $item */
            $permissions[ $item->getId() ]['canUpdate'] = $user->can('updateNews', ['news' => $item]);
            $permissions[ $item->getId() ]['canDelete'] = $user->can('deleteNews', ['news' => $item]);
        }
        return $permissions;
    }
}