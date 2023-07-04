<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use app\assets\AppAsset;

use app\components\NavigationWidget\NavigationWidget;
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

        <?php if ( Yii::$app->user->isGuest === false ):?>
            <?= NavigationWidget::widget(compact('pages'))?>
        <?php endif; ?>


        <div class="container">

            <?= $content ?>

        </div>

    </div>

    <?php if ( Yii::$app->user->isGuest === false ):?>
        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Pleer.ru <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>
    <?php endif; ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>