<?php

namespace app\controllers;

use app\models\Cdr;
use app\models\Page;
use Yii;

class CallCenterController extends AppController
{
    /**
     * Экшн для отображения списка видимых операторов
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Page();
        $pages = $model->getPagesByParent(10);

        $dropPages = '26,27,28,29,30,31';
        $pages = $model->dropMenuButtons($pages, $dropPages);

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/call-center.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index',compact('pages'));
    }

    /**
     * Экшн для отображения результатов поиска для 'call-center'
     *
     * @return string
     */
    public function actionShow()
    {
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $post = $_POST;

                $model = New Cdr();
                $statistic = $model->getStatisticCallCenter($post);

            }

            if ( isset($statistic) ){

                if ( is_string($statistic) ){

                    return json_encode($statistic);

                } elseif (is_array($statistic)){

                    if ($statistic !== []){

                        $this->layout = 'empty';
                        return json_encode($this->render('result',compact('statistic')));

                    }

                }

            }

        }

        return $message;
    }

}