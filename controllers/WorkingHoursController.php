<?php


namespace app\controllers;

use app\models\Login_Out;
use Yii;
use app\models\Page;

class WorkingHoursController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшен для отображения рабочего времени
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Page();
        $pages = $model->getPagesByParent(10);

        $dropPages = '21,22,27,28,29,30,31';
        $pages = $model->dropMenuButtons($pages, $dropPages);


        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/highcharts/css/bootstrap.min.css', ['depends' => 'app\assets\AppAsset']);

        $this->view->registerCssFile('@web/css/nav-management-call-center.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/working-hours.css', ['depends' => 'app\assets\AppAsset']);

        $this->view->registerCssFile('@web/css/main.css', ['depends' => 'app\assets\AppAsset']);

        $this->view->registerJsFile('@web/highcharts/js/jquery-1.12.3.min.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerJsFile('@web/highcharts/js/moment-with-locales.min.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerJsFile('@web/highcharts/js/bootstrap.min.js', ['depends' => 'yii\web\YiiAsset']);

        $this->view->registerJsFile('https://code.highcharts.com/highcharts.src.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['depends' => 'yii\web\YiiAsset']);
        $this->view->registerJsFile('https://code.highcharts.com/highcharts-more.js', ['depends' => 'yii\web\YiiAsset']);

        $this->view->registerJsFile('@web/highcharts/js/main.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('pages'));
    }

    /**
     * Экшн для отображения графиков рабочего времени
     *
     * @return string
     */
    public function actionCharts()
    {

        $sessions = '';

        if (isset($_POST['_csrf'])){

            $model = New Login_Out();
            $sessions = $model->getSessions($_POST);

        }

        return json_encode($sessions);

    }
}