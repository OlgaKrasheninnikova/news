<?php

use yii\db\Migration;

/**
 * Class m180328_195009_manager_role
 */
class m180328_195009_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $admin->description = 'Админ';
        $auth->add($admin);

        $manager = $auth->createRole('manager');
        $manager->description = 'Менеджер';
        $auth->add($manager);

        // add "updatePost" permission
        $viewNews = $auth->createPermission('viewNews');
        $viewNews->description = 'Просматривать новости целиком';
        $auth->add($viewNews);

        // add "createPost" permission
        $createNews = $auth->createPermission('createNews');
        $createNews->description = 'Создавать новости';
        $auth->add($createNews);

        // add "updatePost" permission
        $updateNews = $auth->createPermission('updateNews');
        $updateNews->description = 'Редактировать новости';
        $auth->add($updateNews);

        // add "deletePost" permission
        $deleteNews = $auth->createPermission('deleteNews');
        $deleteNews->description = 'Удалять новости';
        $auth->add($deleteNews);

        $auth->addChild($admin, $createNews);
        $auth->addChild($manager, $createNews);
        $auth->addChild($admin, $updateNews);
        $auth->addChild($admin, $deleteNews);

        $auth->addChild($admin, $manager);



        // add the rule
        $rule = new \app\rbac\AuthorRule;
        $auth->add($rule);

        // add the "updateOwnNews" permission and associate the rule with it.
        $deleteOwnNews = $auth->createPermission('updateOwnNews');
        $deleteOwnNews->description = 'Редактировать собственные новости';
        $deleteOwnNews->ruleName = $rule->name;
        $auth->add($deleteOwnNews);

        $auth->addChild($deleteOwnNews, $updateNews);
        $auth->addChild($manager, $deleteOwnNews);

        // add the "deleteOwnNews" permission and associate the rule with it.
        $deleteOwnNews = $auth->createPermission('deleteOwnNews');
        $deleteOwnNews->description = 'Удалять собственные новости';
        $deleteOwnNews->ruleName = $rule->name;
        $auth->add($deleteOwnNews);

        $auth->addChild($deleteOwnNews, $deleteNews);
        $auth->addChild($manager, $deleteOwnNews);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('auth_item');
        $this->truncateTable('auth_item_child');
        $this->truncateTable('auth_rule');
        $this->truncateTable('auth_assignment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180328_195009_manager_role cannot be reverted.\n";

        return false;
    }
    */
}
