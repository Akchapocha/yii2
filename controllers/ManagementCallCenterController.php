<?php

namespace app\controllers;

use app\models\Agents;
use app\models\Page;
use app\models\QueueLog;
use Yii;

class ManagementCallCenterController extends AppController
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

        $model = New Page();
        $pages = $model->getPagesByParent(10);

        $dropPages = '26,27,28,29,30,31';
        $pages = $model->dropMenuButtons($pages, $dropPages);

        $model = New Agents();
        $operators = $model->getAllFromAgents();

//        debug($operators);

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/management-call-center.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('pages', 'operators'));
    }

    /**
     * Экшн для отображения результатов поиска
     *
     * @return string
     */
    public function actionShow()
    {
        $message = '';

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New QueueLog();
                $operator = $model->getQueueLog($_POST);

                if ( is_string($operator) ){

                    return json_encode($operator);

                } elseif (is_array($operator)) {

                    if ($operator !== []){

                        $this->layout = 'empty';
                        return json_encode( $this->render('result', compact('operator')) );

                    }

                }

            }

        }

        return json_encode($message);

    }

}