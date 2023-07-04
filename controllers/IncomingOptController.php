<?php

namespace app\controllers;

use app\models\Cdr;
use app\models\Page;
use Yii;

class IncomingOptController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения списка входящих оптовых звонков
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

        $operators = [
            0 => 1117,
            1 => 1128,
            2 => 1150,
            3 => 1151,
            4 => 1159,
            5 => 1167,
            6 => 1168,
            7 => 1169,
            8 => 1171,
            9 => 1173,
            10 => 1219,
            11 => 1227
        ];

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/incoming-opt.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/incoming-opt.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('pages', 'operators'));

    }

    /**
     * Экшн для отображения списка входящих оптовых вызовов при применении фильтра
     *
     * @return string
     */
    public function actionShow()
    {

//        debug($_POST);
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Cdr();
                $calls = $model->getInputOptCalls($_POST);

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
                $sheets = $model->getInputOptCalls($_POST, 'return sheets count');

                if ($sheets !== ''){

                    return json_encode($sheets);

                } else {

                    return json_encode(1);

                }


            }

        }

    }

}