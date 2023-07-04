<?php

namespace app\controllers;
use Yii;

class PhonesController extends AppController
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

        Yii::$app->getResponse()->redirect('http://wiki.pleer.ru/doku.php?id=pbxphonebook');
    }

}