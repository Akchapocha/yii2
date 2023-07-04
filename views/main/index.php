<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="body-content container list-page">

    <div class="container head">

        <div class="logo"><a href="http://www.pleer.ru/"><img src="/images/logo.jpg"></a></div>

        <div class="textLogo">
            <a href="mailto:info@pleer.ru">info@pleer.ru</a>
            <p>+7-(495)-775-04-75</p>
            <a href="http://www.pleer.ru/">PLEER.RU</a>
            <p>Управление IP-ATC</p>
        </div>

    </div>

    <?php if ($categories !== []):?>

        <ul>

        <?php foreach ($categories as $item => $category):?>

            <li><?= $category['name_button'];?>

                <?php if ($category['pages'] !== []):?>

                    <ul>

                        <?php foreach ($category['pages'] as $key => $page):?>

                            <?php if ( ($page['name_button'] === 'Монитор супервизора') OR ($page['name_button'] === 'Управление СУБД') ):?>

                                <li><?= Html::a($page['name_button'], Url::to($page['src_page']), ['target' => '_blank']);?></li>

                            <?php else:?>

                                <li><?= Html::a($page['name_button'], Url::to($page['src_page']));?></li>

                            <?php endif;?>

                        <?php endforeach;?>

                    </ul>

                <?php endif;?>

            </li>

        <?php endforeach;?>

        </ul>

    <?php endif;?>


</div>

