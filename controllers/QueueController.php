<?php


namespace app\controllers;

use app\models\Agents;
use app\models\Cdr;
use app\models\Page;
use Yii;


class QueueController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения 'Очереди'
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
        $queue = $model->getQueue();

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/queue.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/queue.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('pages', 'queue'));
    }

    /**
     * Экшн для отображения результатов поиска для 'Очереди'
     *
     * @return string
     */
    public function actionShow()
    {
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                ini_set('memory_limit', '1024M');

                $model = New Cdr();
                $queue = $model->getQueue($_POST);

                ini_set('memory_limit', '128M');

                if ( is_string($queue) ){

                    return json_encode($queue);

                } elseif (is_array($queue)) {

                    if ($queue !== []){

                        $this->layout = 'empty';
                        return json_encode( $this->render('result', compact('queue')) );

                    }

                }

            }

        }

        return $message;
    }



}