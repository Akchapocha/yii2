<?php


namespace app\controllers;

use Yii;
use app\models\Records;

class FileRecordsController extends AppController
{
    /**
     * Установка шаблона
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Экшн для отображения 'Прямого доступа к файлам'
     *
     * @return string
     */
    public function actionIndex()
    {
        self::accessCheck();

        $model = New Records();
        $directories = $model->getDirectories();

        $this->view->title = self::getTitle();

        $this->view->registerCssFile('@web/css/operators.css', ['depends' => 'app\assets\AppAsset']);
        $this->view->registerCssFile('@web/css/file-records.css', ['depends' => 'app\assets\AppAsset']);

        $this->view->registerJsFile('@web/js/file-records.js', ['depends' => 'yii\web\YiiAsset']);

        return $this->render('index', compact('directories'));
    }

    /**
     * Экшн для построения пагинации и изменения отображения
     * при выборе количества "строк на транице", номера страницы
     *
     * @return string
     */
    public function actionDir()
    {
        $this->layout = 'empty';

        if (isset($_POST['_csrf'])) {

            if (Yii::$app->request->getCsrfTokenFromHeader() === $_POST['_csrf']) {

                $model = New Records();
                $records = $model->getRecords($_POST);

                if ( is_array($records) ){

                    $numOfPage = $_POST['numOfPage'];
                    $strOnPage = $_POST['strOnPage'];
                    $nameDir = $_POST['nameDir'];
                    $rangeStrOnPage = [
                        0 => 50,
                        1 => 100,
                        2 => 200,
                        3 => 300,
                        4 => 400,
                        5 => 500
                    ];

                    $pagStrCount = 11;/**Максимальное количество указателей страниц в пагинации, желательно нечетное*/

                    $countPages = round(count($records)/$strOnPage, 0, PHP_ROUND_HALF_UP);

                    if ( $countPages < $pagStrCount ){
                        $countPages = $countPages + 1;
                    }

                    $arrNumStr = $model->getArrNumStr($countPages, $numOfPage, $pagStrCount);

                    $onePage = $model->getOnePage($records, $strOnPage, $numOfPage);



                    if ( is_array($onePage) ){

                        return json_encode($this->render('records', compact('onePage', 'strOnPage', 'numOfPage', 'countPages', 'nameDir', 'rangeStrOnPage', 'pagStrCount', 'arrNumStr')));

                    } else {

                        return json_encode($onePage);

                    }

                } else {

                    return json_encode($records);

                }

            }

        }

        return json_encode('');

    }
}