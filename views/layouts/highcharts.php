<?php

use yii\helpers\Url;
use app\components\NavigationWidget\NavigationWidget;

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Статистика</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Access-Control-Allow-Origin" content="*"/>
        <meta name="Access-Control-Allow-Headers" content="*"/>


        <link type="image/png" href="<?= Url::to('@web/images/favicon.ico')?>" rel="icon">

        <link href="<?= Url::to('@web/highcharts/css/bootstrap.min.css')?>" rel="stylesheet">
        <link rel="stylesheet" href="<?= Url::to('@web/css/nav-management-call-center.css')?>">
        <link rel="stylesheet" href="<?= Url::to('@web/css/working-hours.css')?>">
        <link rel="stylesheet" href="<?= Url::to('@web/css/main.css')?>">


        <script src="<?= Url::to('highcharts/js/jquery-1.12.3.min.js')?>"></script>
        <script src="<?= Url::to('highcharts/js/moment-with-locales.min.js')?>"></script>
        <script src="<?= Url::to('highcharts/js/bootstrap.min.js')?>"></script>

        <script src="https://code.highcharts.com/highcharts.src.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>

        <script src="<?= Url::to('highcharts/js/main.js')?>"></script>
    </head>

    <body>

        <?php echo NavigationWidget::widget(compact('pages'))?>

        <?= $content;?>

    </body>

</html>