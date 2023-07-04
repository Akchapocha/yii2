<?php

use yii\helpers\Html;
use yii\helpers\Url;

$url = Yii::$app->request->url;

//debug($pages);

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

                        <?php if ( intval($pages[$item]['id']) === 27 ):?>

                            <li id="li">Записи:</li>

                        <?php endif;?>


                        <?php if ( intval($page['id']) === 30):?>

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

    <div class="find-form">

        <label>

            Период с:
            <input name="date_start" type="date" value="<?= date('Y-m-d')?>">

            по:
            <input name="date_end" type="date" value="<?= date('Y-m-d')?>">

        </label>

        <label>Очередь: 1605 и 1607</label>

        <label>Оператор:

            <select class="operator">

                <?php if ($operators !== []):?>

                    <?php foreach ($operators as $iem => $operator):?>
                        <option value="<?= $operator;?>"><?= $operator;?></option>
                    <?php endforeach;?>

                <?php endif;?>

                <option selected>-</option>

            </select>

        </label>

        <label>Часть номера телефона:

            <input type="text" name="phone" class="phone">

        </label>

        <label>стр.:

            <select class="pages">

                <option selected>1</option>

            </select>

        </label>

        <button type="button">Показать</button>

    </div>

    <div class="container tbl">



    </div>

</div>