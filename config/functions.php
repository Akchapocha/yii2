<?php

/**
 * Функция распечатки массивов обектов
 *
 * @param $arr
 * @param int $stop если =1, то после распечатки остановка
 */
function debug($arr, $stop = 0)
{
    echo '<pre>';
    print_r($arr);

    if ($stop === 0){
        echo '</pre>';
    } else {
        exit ('</pre>');
    }
}


