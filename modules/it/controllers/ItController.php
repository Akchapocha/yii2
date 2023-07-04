<?php

namespace app\modules\it\controllers;

use Yii;

use app\controllers\AppController;

use app\modules\it\models\ChangeGroup;
use app\modules\it\models\ChangeUser;

use app\modules\it\models\Page;
use app\modules\it\models\Group;
use app\modules\it\models\User;

use yii\db\Exception;
use yii\helpers\Json;

/**
 * Контроллер для модуля 'it'
 *
 * Default controller for the `it` module
 */
class ItController extends AppController
{
    /**
     * Экшн для index
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $this->view->title = self::getTitle();

        $model = New Group();
        $groups = $model->getGroups();

        $model = New Page();
        $pages = $model->getAllPages();

        $this->view->registerJsFile('@web/js/it.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerCssFile('@web/css/it.css', ['depends' => 'app\assets\AppAsset']);

        return $this->render('index', compact('groups', 'pages'));
    }

    /**
     * Экшн установки/смены правил просмотра страниц для групп пользователей
     *
     * @throws Exception
     * @return json
     */
    public function actionApplyGroupRule()
    {
        $message = json_encode('');

        if (isset($_POST['_csrf'])){

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']){
                $post = $_POST;
                $model = New ChangeGroup();
                $message = $model->createMessage($post);
                self::setRules(Yii::$app->user->identity);
            }

        }

        return $message;
    }

    /**
     * Экшн поиска пользователей через строку поиска
     *
     * @return Json
     */
    public function actionFindUser()
    {
        $users = [];

        if (isset($_POST['_csrf'])){

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']){
                $post = $_POST;
                $model = New User();
                $users = $model->findUser($post['findUser']);
            }

        }

        return json_encode($users);
    }

    /**
     * Экшн установки/смены правил просмотра страниц для пользователей
     *
     * @return string|json
     */
    public function actionApplyUserRule()
    {
        $message = json_encode('');

        if (isset($_POST['_csrf'])){

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']){
                $post = $_POST;
                $model = New ChangeUser();
                $message = $model->createMessage($post);
                self::setRules(Yii::$app->user->identity);
            }

        }

        return $message;
    }

}