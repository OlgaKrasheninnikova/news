<?php

use yii\db\Migration;

/**
 * Class m180328_110426_news
 */
class m180328_110430_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `news` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `description` varchar(255) NOT NULL,
          `image` varchar(255) DEFAULT NULL,
          `text` text NOT NULL,
          `date` date NOT NULL,
          `is_active` tinyint DEFAULT 0,
          `created_user_id` int(11) NOT NULL,
          `created_at` date NOT NULL,
          `updated_user_id` int(11) DEFAULT NULL,
          `updated_at` date DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `date` (`date`),
           CONSTRAINT `created_user_id` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
           CONSTRAINT `updated_user_id` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `news`");
        return true;
    }
}
