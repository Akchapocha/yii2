<?php

use yii\helpers\Url;
//debug($calls);
?>

<table class="data_tbl">

    <tr class="grd_head">
        <td width="1">№</td>
        <td>Дата / Время</td>
        <td>Исходящий</td>
        <td>Ход звонка</td>
        <td>Длительность</td>
        <td>Запись</td>
    </tr>

    <?php if ( isset($calls) ):?>

        <?php foreach ($calls as $item => $call):?>

            <tr class="grd_row">
                <td><?= $item+1;?></td>
                <td><?= $call['call_date'];?></td>
                <td><?= $call['from_who'];?></td>
                <td><?= $call['to_who'];?></td>
                <td><?= $call['duration'];?></td>

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
