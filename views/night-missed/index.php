<?php

use yii\helpers\Html;

?>

<div class="body-content">

    <div class="container">

        <?php if (isset($callNightMissed)):?>

            <?php if ($callNightMissed !== []):?>

                <?php foreach ($callNightMissed as $item => $value):?>

                    <?= $value['calldate'] . ' - ' . $value['clid'] . '<br>';?>

                <?php endforeach;?>

            <?php endif;?>

        <?php endif;?>

    </div>

</div>