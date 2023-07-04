<?php


namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

class Login_Out extends ActiveRecord
{
    /**
     * Имя БД
     *
     * @return object|Connection|null
     * @throws InvalidConfigException
     */
    public static function getDb() {
        return Yii::$app->get('cc');
    }

    /**
     * Имя таблицы в БД
     *
     * @return string
     */
    public static function tableName()
    {
        return 'login_out';
    }

    /**
     * Проверяем введенные данные и получаем массив логов из БД
     *
     * @param $post - введенные данные
     * @return array|string - массив для дальнейше обработки в js|сообщение об ошибке
     */
    public function getSessions($post)
    {
        $and = '';
        $ts_from = '';
        $ts_to = '';

        if ( isset($post['number']) AND isset($post['date_start']) AND isset($post['date_end']) ){

            if ( ($post['number'] !== '') AND (is_numeric($post['number'])) ){

                if ( preg_match('/^16\d{2}$/', $post['number']) ){

                    $and = '`q1` = ' . $post['number'];

                } elseif(preg_match('/^\d{4}$/', $post['number'])) {

                    $and = '`internal` = ' . $post['number'];

                } else {

                    $message = 'Ошибка. Введите корректный добавочный или номер очереди.';
                }

            } else {

                $message = 'Ошибка. Введите добавочный или номер очереди.';

            }

            if ( ($post['date_start'] !== '') AND ($post['date_end'] !== '') ){

                if ( $post['date_start'] > $post['date_end']){

                    $ts_from = $post['date_end'] . ' 00:00:00';
                    $ts_to = $post['date_start'] . ' 23:59:59';

                } else {

                    $ts_from = $post['date_start'] . ' 00:00:00';
                    $ts_to = $post['date_end'] . ' 23:59:59';

                }

            } else {

                $message = 'Ошибка. Укажите период.';

            }

        } else {

            $message = 'Ошибка.';

        }

        if ( isset($message) ){

            return $message;

        }

        $logs = Login_Out::find()
                ->select('`id`, `session_id`, `date_time`, `agent_id`, `agent_name`, `op`, `internal`, `q1`')
                ->where('`date_time` BETWEEN \'' . $ts_from . '\' AND \'' . $ts_to . '\'')
                ->andWhere($and)
                ->asArray()
                ->all();

        if ( isset($logs[0]) ){

            return self::sortMultiDate($logs);

        } else {

            return 'Ничего не найдено.';

        }

    }

    /**
     * Сортируем массив по датам, именам, сессиям
     *
     * @param $input
     * @return array
     */
    private static function sortMultiDate($input)
    {
        $sorted_data = [];
        $newArr = [];

        foreach ($input as $key => $value)
        {
            $input[$key]["date"] = date("d.m.Y", strtotime($input[$key]["date_time"]));
            $input[$key]["date_time"] = (strtotime($input[$key]["date_time"]))*1000;
            $input[$key]["agent_name"] = self::replaceAgentName($input[$key]["agent_name"]);
            $sorted_data[$value['agent_id']][] = $input[$key];
        }

        foreach ($sorted_data as $key => $value){

            $i = 0;

            foreach($sorted_data[$key] as $subkey => $subvalue){

                if (isset($sorted_data[$key][$i+1])) {

                    if (($subvalue['session_id'] == 0) && ($subvalue['agent_id'] == $sorted_data[$key][$i + 1]['agent_id'])) {

                        $subvalue['session_id'] = $sorted_data[$key][$i + 1]['session_id'];

                    }

                }

                $newArr[$subvalue['date']][$subvalue['agent_name']][$subvalue['session_id']][] = $subvalue;
                $i++;
            }

        }

        return $newArr;

    }

    /**
     * Убираем номер добавочного из имени
     *
     * @param $input
     * @return string
     */
    private static function replaceAgentName($input)
    {
        $arr = explode(" ", $input);
        if (count($arr) != 1){
            $newarr = [];
            $newarr[0] = $arr[0];
            $newarr[1] = $arr[1];
            $input = implode(" ", $newarr);
        }

        return $input;
    }

}