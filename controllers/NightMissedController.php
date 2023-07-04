<?php


namespace app\controllers;


use app\models\Cdr;

class NightMissedController extends AppController
{
    /**
     * Экшн для отображения пропущенных вызовов за ночь
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Cdr();
        $callNightMissed = $model->getNightMissed();

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/operators.css', ['depends' => 'app\assets\AppAsset']);

        return $this->render('index', compact('callNightMissed'));
    }
}