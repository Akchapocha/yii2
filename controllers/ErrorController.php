<?php

namespace app\controllers;

use Yii;

/**
 * Класс обработки ошибок
 *
 * Class ErrorController
 * @package app\controllers
 */
class ErrorController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'error';

    /**
     * Экшен для отображения страницы '404'
     *
     * @return string
     */
    public function action404()
    {
        $this->view->title = 'Что-то пошло не так...';

        Yii::$app->response->statusCode = 404;
        $message = 'Такой страницы не существует!';

        return $this->render('404', compact('message'));
    }

    /**
     * Экшен для отображения страницы '403'
     *
     * @return string
     */
    public function action403()
    {
        $this->view->title = 'Что-то пошло не так...';

        Yii::$app->response->statusCode = 403;
        $message = 'У Вас нет доступа к данной странице!';

        return $this->render('404', compact('message'));
    }
}