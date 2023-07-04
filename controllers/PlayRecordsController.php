<?php


namespace app\controllers;



use app\assets\ResourcesAsset;
use app\models\Agents;
use app\models\Cdr;
use app\models\Page;
use Yii;

class PlayRecordsController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения списка входящих вызовов
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

        $model = New Agents();
        $queue = $model->getQueue();

        $model = New Agents();
        $operators = $model->getAllFromAgents();

        $this->view->title = self::getTitle();

//        debug(RECORDS);

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/play-records.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/play-records.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('pages', 'queue', 'operators'));

    }

    /**
     * Экшн для отображения списка входящих вызовов при применении фильтра
     *
     * @return string
     */
    public function actionShow()
    {
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Cdr();
                $calls = $model->getInputCalls($_POST);

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
                $sheets = $model->getInputCalls($_POST, 'return sheets count');

                if ($sheets !== ''){

                    return json_encode($sheets);

                } else {

                    return json_encode(1);

                }


            }

        }

    }
}