<?php


namespace app\controllers;

use Yii;

class ManagementServerController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения 'Call-центра'
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        Yii::$app->getResponse()->redirect('http://pbx.pleer.ru/zabbix/?db=asterisk');

    }
}