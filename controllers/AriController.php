<?php

namespace app\controllers;

use Yii;

class AriController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшен для отображения главной страницы
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        Yii::$app->getResponse()->redirect('http://192.168.0.19/recordings/');

    }
}