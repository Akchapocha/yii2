<?php

namespace app\controllers;

use app\models\Agents;
use app\models\Page;
use Yii;
use yii\helpers\Json;

class OperatorsHiddenController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения списка скрытых операторов
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Agents();
        $operators = $model->getHiddenAgents();

        $model = New Page();
        $pages = $model->getPagesByParent(10);

        $dropPages = '21,22,24,27,28,29,30,31';
        $pages = $model->dropMenuButtons($pages, $dropPages);

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/operators.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/operators.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerJsFile('@web/js/operators-hidden.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('operators', 'pages'));
    }

    /**
     * Экшн для получения данных о свободных "pin"
     *
     * @return string - список либо один из списка доступных 'pin'
     */
    public function actionGetPins()
    {

        if ( isset($_POST['_csrf']) and isset($_POST['action']) ) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Agents();
                $busyPins = $model->getPins($_POST['action']);

                return json_encode($busyPins);

            }

        }

    }

    /**
     * Экшн для добавления нового оператора
     *
     * @throws \Throwable
     * @return Json
     */
    public function actionCreate()
    {
//        self::accessCheck();
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Agents();
                $message = $model->createOperator($_POST);

            }

        }

        return $message;
    }

    /**
     * Экшн для окончательного удаления пользователя
     *
     * @return Json
     */
    public function actionDelete()
    {
//        self::accessCheck();
        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Agents();
                $message = $model->deleteOperator($_POST['idAgent']);

            }

        }

        return $message;
    }
}