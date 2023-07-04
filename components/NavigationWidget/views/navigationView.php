<?php

use yii\helpers\Html;
use yii\helpers\Url;

$url = Yii::$app->request->url;

?>

<nav class="navbar navbar-default">

    <div class="container-fluid">

        <?php if (Yii::$app->user->isGuest !== true):?>
            <div class="navbar-header">
                <span class="navbar-brand">Вы авторизованы как: <?= Yii::$app->user->identity->username ?></span>
            </div>
        <?php endif; ?>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav">

                <?php foreach ($pages as $page):?>

                    <?php if ( ($page['src_page'] === '/') ):?>

                        <?php if ($url === '/'):?>

                            <li class="active"><?= Html::a($page['name_button'], Url::to($page['src_page']), ['title' => $page['title']])?></li>

                        <?php else:?>

                            <li><?= Html::a($page['name_button'], Url::to($page['src_page']), ['title' => $page['title']])?></li>

                        <?php endif;?>

                    <?php elseif( $page['src_page'] === '/it' ):?>

                        <?php if ($url === '/it'):?>

                            <li class="active"><?= Html::a($page['name_button'], Url::to($page['src_page']), ['title' => $page['title']])?></li>

                        <?php else:?>

                            <li><?= Html::a($page['name_button'], Url::to($page['src_page']), ['title' => $page['title']])?></li>

                        <?php endif;?>

                    <?php else:?>

                            <li class="active"><?= Html::a($page['name_button'], Url::to($page['src_page']), ['title' => $page['title']])?></li>

                    <?php endif;?>

                <?php endforeach;?>

            </ul>



            <ul class="nav navbar-nav navbar-right">
                <li><?= Html::a('выход', Url::to('/logout'))?></li>
            </ul>


        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->

</nav>