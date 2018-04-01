<?php

namespace app\helpers;

use yii\base\InvalidConfigException;

/**
 * Для доступа к пользовательским параметрам
 *
 * Class Config
 * @package app\helpers
 */
class Config {

    private static $instance;

    private $config;


    /**
     * Config constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return Config
     */
    public static function getInstance( ) {
        if (!self::$instance) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * @param string $param
     * @param string $section
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getParam($param, $section = '', $default = '') {
        if (!$this->config) {
            $this->config = \Yii::$app->params;
        }
        if ($section & !isset($this->config[$section])) {
            return $default;
        }
        $config = $this->config[$section];
        if (isset($config[$param])) {
            return $config[$param];
        } else {
            return $default;
        }
    }
}