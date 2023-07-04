<?php

namespace app\controllers;

use app\models\Agents;
use app\models\Page;
use Throwable;
use Yii;
use yii\helpers\Json;

class OperatorsVisibleController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения списка видимых операторов
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Agents();
        $operators = $model->getVisibleAgents();

        $model = New Page();
        $pages = $model->getPagesByParent(10);

        $dropPages = '21,22,27,28,29,30,31';
        $pages = $model->dropMenuButtons($pages, $dropPages);

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/operators.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerJsFile('@web/js/operators.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerJsFile('@web/js/operators-visible.js', ['depends' => 'yii\web\YiiAsset']);

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
     * Экшн для получения данных оператора для формы редактирования
     *
     * @return string
     */
    public function actionGetOperator()
    {

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Agents();
                $operator = $model->getAgentById($_POST['idAgent']);

                return json_encode($operator);

            }

        }

    }

    /**
     * Экшн для сохранения отредактированного опреатора
     *
     * @return string
     */
    public function actionEdit()
    {
        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Agents();
                $message = $model->saveOperator($_POST);

                return json_encode($message);

            }

        }
    }

    /**
     * Экшн для скрытия оператора из списка видимых
     *
     * @return string
     */
    public function actionHide()
    {
//        self::accessCheck();

        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                if (isset($_POST['idAgent'])){

                    $model = New Agents();
                    $message = $model->hideOperator($_POST['idAgent']);

                } else {

                    $message = json_encode('Не удалось скрыть оператора.');

                }

            }

        }

        return $message;
    }

    /**
     * Экшн для добавления нового оператора
     *
     * @throws Throwable
     * @return string
     */
    public function actionCreate()
    {

        $message = json_encode('');

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Agents();
                $message = $model->createOperator($_POST);

            }

        }

        return $message;
    }


}