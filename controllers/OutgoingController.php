<?php

namespace app\controllers;

use app\models\Agents;
use app\models\Cdr;
use app\models\Page;
use Yii;

class OutgoingController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения списка исходящих вызовов
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Page();
        $pages = $model->getPagesByParent(10);

        $dropPages = '21,23,25,26';
        $pages = $model->dropMenuButtons($pages, $dropPages);

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/outgoing.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/outgoing.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('pages'));

    }

    /**
     * Экшн для отображения списка исходящих вызовов при применении фильтра
     *
     * @return string
     */
    public function actionShow()
    {
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Cdr();
                $calls = $model->getOutgoingCalls($_POST);

                if ( is_string($calls) ){

                    return json_encode($calls);

                } elseif (is_array($calls)) {

                    if ($calls !== []){

                        $this->layout = 'empty';
                        return json_encode( $this->render('result', compact('calls')) );

                    }

                }

            }

        }

        return $message;
    }

    /**
     * Экшн для изменения поля "стр.:"
     *
     * @return string
     */
    public function actionSheets()
    {

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Cdr();
                $sheets = $model->getOutgoingCalls($_POST, 'return sheets count');

                if ($sheets !== ''){

                    return json_encode($sheets);

                } else {

                    return json_encode(1);

                }


            }

        }

    }
}