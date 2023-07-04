<?php

/* @var $this \yii\web\View */
/* @var $content string */


use app\assets\AppAsset;
use app\assets\RecordsAsset;

use app\components\NavigationWidget\NavigationWidget;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/images/favicon.ico'])]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <?php echo NavigationWidget::widget(compact('pages'))?>

    <?= $content ?>

</div>

<div class="container preload">
    <b class="loading"></b>
    <p>Подождите. Идет агрузка данных.</p>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Pleer.ru <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
