<?php

$queue = $statistic;

$summCalls = 0;
$summAnswer = 0;
$summMissed = 0;

$summTimeWaitAnswer = 0;
$summTimeWaitMissed = 0;
$summTimeTalk = 0;
$summTimeDuration = 0;

?>

<table class="data_tbl">

    <tr class="grd_head">
        <td rowspan=2>Дата</td>
        <td>Поступило</td>
        <td colspan=2>Принято</td>
        <td colspan=2>Потеряно</td>
        <td colspan=4>Средние значения</td>
    </tr>

    <tr class="grd_head">
        <td>Кол-во</td>
        <td>Кол-во</td>
        <td>%</td>
        <td>Кол-во</td>
        <td>%</td>
        <td>Ожидание (для принятых)</td>
        <td>Ожидание (для потерянных)</td>
        <td>Разговор (для принятых)</td>
        <td>Обслуживание вызова</td>
    </tr>

    <?php foreach ($queue as $date => $values):?>

        <?php

            if (isset($values['allCalls'])){
                $summCalls = $summCalls + $values['allCalls'];
            }

            if (isset($values['allAnsweredCalls'])){
                $summAnswer = $summAnswer + $values['allAnsweredCalls'];
            }


            if (isset($values['allMissedCalls'])){
                $summMissed = $summMissed + $values['allMissedCalls'];
            }

            if (isset($values['waitAnsweredCalls']) ){
                $summTimeWaitAnswer = $summTimeWaitAnswer + $values['waitAnsweredCalls'];
            }

            if (isset($values['waitMissedCalls']) ){
                $summTimeWaitMissed = $summTimeWaitMissed + $values['waitMissedCalls'];
            }

            if (isset($values['talkCalls']) ){
                $summTimeTalk = $summTimeTalk + $values['talkCalls'];
            }

            if (isset($values['callsDuration']) ){
                $summTimeDuration = $summTimeDuration + $values['callsDuration'];
            }


        ?>

        <tr class="grd_row">
            <td><?= $date;?></td>
            <td><?= $values['allCalls']?></td>

            <?php if ( isset($values['allAnsweredCalls']) ):?>

                <td><?= $values['allAnsweredCalls']?></td>

            <?php else:?>

                <td>0</td>

            <?php endif;?>


            <?php if ( isset($values['allAnsweredCalls']) AND isset($values['allCalls']) ):?>

                <?php if ( $values['allCalls'] > 0 ):?>

                    <td><?= round( 100*($values['allAnsweredCalls']/$values['allCalls']),0)?></td>

                <?php else:?>

                    <td>-</td>

                <?php endif;?>

            <?php else:?>

            <td>-</td>

            <?php endif;?>



            <?php if ( isset($values['allMissedCalls']) ):?>

                <td><?= $values['allMissedCalls']?></td>

            <?php else:?>

                <td>0</td>

            <?php endif;?>


            <?php if ( isset($values['allMissedCalls']) AND isset($values['allCalls']) ):?>

                <?php if ( $values['allCalls'] > 0 ):?>

                    <td><?= round( 100*($values['allMissedCalls']/$values['allCalls']),0)?></td>

                <?php else:?>

                    <td>-</td>

                <?php endif;?>

            <?php else:?>

                <td>-</td>

            <?php endif;?>


            <?php if ( isset($values['waitAnsweredCalls']) AND  isset($values['allAnsweredCalls']) ):?>

                <?php if ( $values['allAnsweredCalls'] > 0 ):?>

                    <td><?= date('H:i:s', $values['waitAnsweredCalls']/$values['allAnsweredCalls'])?></td>

                <?php else:?>

                    <td>-</td>

                <?php endif;?>

            <?php else:?>
                <td>-</td>
            <?php endif;?>

            <?php if ( isset($values['waitMissedCalls']) AND isset($values['allMissedCalls']) ):?>

                <?php if ( $values['allMissedCalls'] > 0 ):?>

                    <td><?= date('H:i:s', $values['waitMissedCalls']/$values['allMissedCalls'])?></td>

                <?php else:?>

                    <td>-</td>

                <?php endif;?>

            <?php else:?>
                <td>-</td>

            <?php endif;?>

            <?php if ( isset($values['talkCalls']) AND isset($values['allAnsweredCalls']) ):?>

                <?php if ($values['allAnsweredCalls'] > 0):?>

                    <td><?= date('H:i:s', $values['talkCalls']/$values['allAnsweredCalls'])?></td>

                <?php else:?>

                    <td>-</td>

                <?php endif;?>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

            <?php if ( isset($values['callsDuration']) AND isset($values['allCalls']) ):?>

                    <?php if ( $values['allCalls'] > 0 ):?>

                        <td><?= date('H:i:s', $values['callsDuration']/$values['allCalls'])?></td>

                    <?php else:?>

                        <td>-</td>

                    <?php endif;?>

            <?php else:?>

                <td>-</td>

            <?php endif;?>


        </tr>

    <?php endforeach;?>


    <tr class="grd_bottom">
        <td>Итоговые</td>
        <td><?= $summCalls?></td>
        <td><?= $summAnswer?></td>

        <?php if ( isset($summAnswer) AND isset($summCalls) ):?>

            <?php if ($summCalls > 0):?>

                <td><?= round( 100* ($summAnswer/$summCalls), 0)?></td>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

        <?php else:?>

            <td>-</td>

        <?php endif;?>

        <td><?= $summMissed?></td>

        <?php if ( isset($summMissed) AND isset($summCalls) ):?>

            <?php if ($summCalls > 0):?>

                <td><?= round( 100* ($summMissed/$summCalls), 0)?></td>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

        <?php else:?>

            <td>-</td>

        <?php endif;?>


        <?php if ( isset($summTimeWaitAnswer) AND isset($summAnswer) ):?>

            <?php if ($summAnswer > 0):?>

                <td><?= date( 'H:i:s', ($summTimeWaitAnswer/$summAnswer))?></td>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

        <?php else:?>

            <td>-</td>

        <?php endif;?>

        <?php if ( isset($summTimeWaitMissed) AND isset($summMissed) ):?>

            <?php if ($summMissed > 0):?>

                <td><?= date( 'H:i:s', ($summTimeWaitMissed/$summMissed))?></td>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

        <?php else:?>

            <td>-</td>

        <?php endif;?>

        <?php if ( isset($summTimeTalk) AND isset($summAnswer) ):?>

            <?php if ($summAnswer > 0):?>

                <td><?= date( 'H:i:s', ($summTimeTalk/$summAnswer))?></td>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

        <?php else:?>

            <td>-</td>

        <?php endif;?>

        <?php if ( isset($summTimeDuration) AND isset($summCalls) ):?>

            <?php if ($summCalls > 0):?>

                <td><?= date( 'H:i:s', ($summTimeDuration/$summCalls))?></td>

            <?php else:?>

                <td>-</td>

            <?php endif;?>

        <?php else:?>

            <td>-</td>

        <?php endif;?>
    </tr>

</table>