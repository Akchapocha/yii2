<?php


namespace app\controllers;
use Yii;

class StatisticCallCenterController extends AppController
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

        Yii::$app->getResponse()->redirect('/management-call-center');
    }
}