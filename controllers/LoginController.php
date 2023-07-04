<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;

/**Параметры для подключения LDAP*/
define('LDAP', require_once __DIR__. '/../config/ldap.php');

/**
 * Класс для работы со страницей авторизации
 *
 * Class LoginController
 * @package app\controllers
 */
class LoginController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'login';

    /**
     * Экшен для для вывода формы и авторизации
     *
     * @return string| yii\web\Response
     */
    public function actionIndex()
    {
        $message = '';

        if (!Yii::$app->user->isGuest) {

            return $this->goHome();

        }

        $model = new LoginForm();

        $post = $model->load(Yii::$app->request->post());

        $login = $model->login();

        if ($post && $login) {

                self::setRules(Yii::$app->user->identity);
                return $this->goHome();

        }

        if ($post && !$login) {

            $message = 'Такого пользователя не существует!';

        }

        $model->password = '';

        $this->view->registerJsFile('@web/js/login.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerCssFile('@web/css/login.css', ['depends' => 'app\assets\AppAsset']);

        return $this->render('login', compact('model', 'message'));

    }

    /**
     * Экшен для выхода авторизованного пользователя
     *
     * @return yii\web\Response
     */
    public function actionLogout()
    {
        if (isset($_SESSION['rulesResult'])){

            unset($_SESSION['rulesResult']);

        }

        Yii::$app->user->logout();

        return $this->goHome();
    }
}