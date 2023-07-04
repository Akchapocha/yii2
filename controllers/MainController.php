<?php

namespace app\controllers;
use app\models\Page;
/**
 * Класс для работы с главной страницей
 *
 * Class MainController
 * @package app\controllers
 */
class MainController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшен для отображения главной страницы
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Page();
        $categories = $model->getPagesByGroup();

        $this->view->title = self::getTitle();

        return $this->render('index', compact('categories'));
    }


}