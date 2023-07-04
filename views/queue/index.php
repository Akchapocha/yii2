<?php

use yii\helpers\Html;
use yii\helpers\Url;

$url = Yii::$app->request->url;

?>

<nav class="navbar navbar-default operators">


    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav">

                <li id="li">Статистика:</li>

                <?php if ($pages !== []):?>

                    <?php foreach ($pages as $item => $page):?>

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

    <div class="find-form">

        <label>

            Период с:
            <input name="date_start" type="date" value="<?= date('Y-m')?>-01">

            по:
            <input name="date_end" type="date" value="<?= date('Y-m-d')?>">

        </label>

        <label>Очередь:

            <select>

                <?php if ($queue !== []):?>

                    <?php foreach ($queue as $item => $que):?>
                        <option value="<?= $que;?>"><?= $que;?></option>
                    <?php endforeach;?>

                <?php endif;?>

                <option selected>-</option>

            </select>

        </label>

        <button type="button">Показать</button>

    </div>

    <div class="container tbl">

        <table class="data_tbl">

            <tr class="grd_head">
                <td rowspan=2>Дата</td>
                <td>Поступило</td>
                <td colspan=2>Принято</td>
                <td colspan=2>Пропущено</td>
                <td colspan=2>Исходящие</td>
                <td colspan=4>Средние значения</td>
            </tr>

            <tr class="grd_head">
                <td>Кол-во</td>
                <td>Кол-во</td>
                <td>%</td>
                <td>Кол-во</td>
                <td>%</td>
                <td>Кол-во</td>
                <td>%</td>
                <td>Ожидание</td>
                <td>Разговор</td>
                <td>Обслуживание вызова</td>
            </tr>

            <tr class="grd_bottom">
                <td>Итоговые</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0:00:00</td>
                <td>0:00:00</td>
                <td>0:00:00</td>
                <td>0:00:00</td>
            </tr>

        </table>

    </div>

</div>