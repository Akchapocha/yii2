<?php


namespace app\controllers;


class StatisticZakupController extends AppController
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

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/operators.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/operators.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index');
    }
}