<?php


namespace app\models;

class Records
{
    /**
     * Получаем имена папок, находящихся в корне папки с записями
     *
     * @return array|string
     */
    public function getDirectories()
    {
        $directories = scandir(RECORDS);

        if ( isset($directories[2]) ){

            unset($directories[0]);
            unset($directories[1]);

            return self::getLastModify($directories);

        } else {

            return 'Файлы записей не обнаружены.';

        }

    }

    /**
     * Получаем массив всех записей находящихся в одной папке
     *
     * @param $post
     * @return array|string
     */
    public function getRecords($post)
    {
        $message = '';
        $records = [];

        if (isset($post['nameDir'])){

            $records = scandir(RECORDS.$post['nameDir']);

        } else {

            $message = 'Ошибка.';

        }

        if ($message === ''){

            if ( isset($records[2]) ){

                unset($records[0]);
                unset($records[1]);

                return self::getSrcRecords($records, $post['nameDir']);


            } else {

                return 'Ошибка. В данной папке нет ни одной записи.';

            }

        } else {

            return $message;

        }

    }

    /**
     * Получаем массив записей для отображения на конкретной странице пагинатора
     *
     * @param $records
     * @param $strOnPage
     * @param $numOfPage
     * @return array|string
     */
    public function getOnePage($records, $strOnPage, $numOfPage)
    {
        $res = [];

        $firstRow = $strOnPage * ($numOfPage - 1);
        $lastRow = ($strOnPage * $numOfPage) - 1;


        foreach ($records as $item => $value){

            if ( ($item >= $firstRow) AND ($item <= $lastRow) ){

                $res[] = $value;

            }

        }

        if ( isset($res[0]) ){

            return $res;

        } else {

            return 'Ошибка.';
        }


    }

    /**
     * Получаем массив номеров страниц для пагинации,
     * так чтобы текущая страница была,по возможности в центре
     *
     * @param $countPages - количество страниц
     * @param $numOfPage - номер текущей страницы
     * @param $pagStrCount - максимальное количество указателей страниц в пагинации
     *
     * @return array
     */
    public function getArrNumStr($countPages, $numOfPage, $pagStrCount)
    {
        $res = [];

        $delta = round($pagStrCount/2, 0, PHP_ROUND_HALF_DOWN);


        $pagStart = $numOfPage - $delta;
        $pagEnd = $numOfPage + $delta;

        if ($numOfPage <= ($delta+1) ){

            $pagStart = 1;
            $pagEnd = $pagStrCount;

        }

        if ( ($numOfPage + $delta) >=  $countPages){

            $pagStart = ($countPages - $pagStrCount) + 1;
            $pagEnd = $countPages;

        }

        if ( $countPages < $pagStrCount){

            $pagStart = 1;
            $pagEnd = $countPages;

        }


        for ($i = $pagStart; $i <= $pagEnd; $i++){

            $res[] = $i;

        }

        return $res;
    }

    /**
     * Получаем дату и время последнего изменения каждой папки
     *
     * @param $directories
     * @return array
     */
    private static function getLastModify($directories)
    {
        $i = 0;
        $res = [];

        foreach ($directories as $item => $value){

            $res[$i][0] = $value;
            $res[$i][1] = date('d-M-Y H:i', filemtime(RECORDS.$value));

            $i++;
        }

        return $res;

    }

    /**
     * @param $nameRecords
     * @param $nameDir
     * @return array|mixed
     */
    private static function getSrcRecords($nameRecords, $nameDir)
    {
        $res = [];
        $i = 0;

        foreach ($nameRecords as $item => $name){

            $res[$i]['name'] = $name;
            $res[$i]['time'] = date('d-M-Y H:i', filemtime(RECORDS.$nameDir.'/'.$name));
            $res[$i]['src'] = str_replace(WEB,'',RECORDS).$nameDir.'/'.$name;

            $i++;
        }

        return $res;
    }

}