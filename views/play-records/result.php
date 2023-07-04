<?php

use yii\helpers\Url;
//debug($calls['rows']);

?>

<table class="data_tbl">

    <tr class="grd_head">
        <td width="1">№</td>
        <td>Дата / Время</td>
        <td>Оператор</td>
        <td>АОН</td>
        <td>Вн.номер</td>
        <td>Длительность</td>
        <td>Запись</td>
    </tr>

    <?php if ( isset($calls['rows']) ):?>

        <?php foreach ($calls['rows'] as $item => $call):?>

            <tr class="grd_row">
                <td><?= $item+1;?></td>
                <td><?= $call['calldate'];?></td>
                <td><?= $call['agentName'];?></td>
                <td><?= $call['src'];?></td>
                <td><?= $call['dst'];?></td>
                <td><?= $call['billsec'];?></td>

            <?php if ( isset($call['record_src']) ):?>

                <td width=115 align=center>

                    <audio controls="">

                        <source src="<?= Url::to($call['record_src']);?>">
                        <a href="<?= Url::to($call['record_src']);?>">Скачать</a>

                    </audio>

                </td>

            <?php else:?>

                <td width=115 align=center>Файл не найден!</td>

            <?php endif;?>

            </tr>

        <?php endforeach;?>

    <?php endif;?>

</table>



