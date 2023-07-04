<?php

use yii\helpers\Html;
use yii\helpers\Url;

$url = Yii::$app->request->url;

?>

<nav class="navbar navbar-default operators">

    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav">

                <?php if ($pages !== []):?>

                    <?php foreach ($pages as $item => $page):?>

                        <?php
                        if ( intval($page['id']) === 20) {
                            $page['name_button'] = 'Статистика';
                        }
                        ?>

                        <?php if ( intval($pages[$item]['id']) === 26 ):?>

                            <li id="li">Операторы:</li>

                        <?php endif;?>


                        <?php if ($page['src_page'] === $url):?>

                            <li class="active"><?= Html::a($page['name_button'], Url::to($page['src_page']))?></li>

                        <?php elseif($page['src_page'] !== '/operators-hidden'):?>

                            <li><?= Html::a($page['name_button'], Url::to($page['src_page']))?></li>

                        <?php endif;?>

                    <?php endforeach;?>

                <?php endif;?>

            </ul>

        </div>

    </div>

</nav>

<div class="body-content">

    <div class="find">

        <label>Поиск по

            <input type="text" name="number" class="search">

        </label>

        <label>

            Период с:
            <input name="bydate1" type="date" value="<?= date('Y-m-d')?>">

            по:
            <input name="bydate2" type="date" value="<?= date('Y-m-d')?>">

        </label>

        <button type="button">Показать</button>

    </div>

    <div class="col-md-12 content">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="data-content">
                </div>
            </div>
        </div>
    </div>


</div>




