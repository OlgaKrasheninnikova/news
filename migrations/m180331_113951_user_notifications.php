<?php

use yii\db\Migration;

/**
 * Class m180331_113951_user_notifications
 */
class m180331_113951_user_notifications extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //$this->execute('ALTER TABLE {{%user}} ADD COLUMN `notifications_email` boolean AFTER registration_ip DEFAULT false');
        $this->addColumn('{{%user}}', 'notifications_email', \yii\db\mysql\Schema::TYPE_BOOLEAN . ' DEFAULT false NOT NULL');
        $this->addColumn('{{%user}}', 'notifications_push', \yii\db\mysql\Schema::TYPE_BOOLEAN. ' DEFAULT false NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('{{%user}}', 'notifications_email');
        $this->dropColumn('{{%user}}', 'notifications_push');
    }
}
