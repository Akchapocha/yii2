<?php


namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

class Cel extends ActiveRecord
{
    /**
     * Имя БД
     *
     * @return object|Connection|null
     * @throws InvalidConfigException
     */
    public static function getDb() {
        return Yii::$app->get('asteriskcdr');
    }

    /**
     * Имя таблицы в БД
     *
     * @return string
     */
    public static function tableName()
    {
        return 'cel';
    }

    public function getCalls($post)
    {
        $phone = '';
        $eventTime_limit = '';

        $stop_list = array();
        array_push($stop_list, "1001", "1003", "1004", "1050", "1053", "1602");

        if ( isset($post['ts_from']) AND isset($post['phone']) ){

            if ( ($post['ts_from'] === '') AND ($post['phone'] === '') ){

                $message = 'Ошибка. Заполните поля.';


            }

            if ( $post['ts_from'] !== '' ){

                $ts_from = $post['ts_from'] . ' 00:00:00';
                $ts_to = $post['ts_from'] . ' 23:59:59';

            } else {

                $ts_from = date('Y-m-d') . ' 00:00:00';
                $ts_to = date('Y-m-d') . ' 23:59:59';

            }

            if ( ($post['phone'] !== '') AND (is_numeric($post['phone'])) ){

                $phone = $post['phone'];

                if( !in_array($phone, $stop_list) ){

                    if( (strlen($phone) === 11) AND ($post['ts_from'] === '') ) {

                        $ts_from = date("Y-m-d", strtotime("90 days ago"))." 00:00:00";
                        $ts_to = date("Y-m-d")." 23:59:59";

                    }

                    $eventTime_limit="AND eventtime BETWEEN  '".$ts_from."' AND '".$ts_to."'";

                } else {

                    $message = 'Ошибка. Данный добавочный номер находится в стоп-листе.';

                }

            } else {

                $message = 'Ошибка. Укажите номер для поиска.';

            }

        } else {

            $message = 'Ошибка!';

        }

        if (isset($message)){

            return $message;

        }

        $sql = 'SELECT MIN( eventtime ) AS call_date,
                        TIMEDIFF( MAX( eventtime ), MIN( eventtime ) ) AS duration,
                        SUBSTRING_INDEX(
                                        GROUP_CONCAT(DISTINCT IF(eventtype=\'BRIDGE_ENTER\',
                                                                 CONCAT_WS(\'\',
                                                                           cid_num,
                                                                           NULLIF(CONCAT(\'(\',cid_name,\')\'),
                                                                                  CONCAT(\'(\',IF(cid_name,cid_num, \'\'),\')\')
                                                                                  ),
                                                                           NULLIF(NULLIF(cid_ani, SUBSTR(cid_num,2)), cid_num)
                                                                           ),
                                                                 NULL
                                                                 )
                                                     ORDER BY uniqueid SEPARATOR \'->\'
                                                    ),
                                        \'->\', 1
                                        ) as from_who,
                        GROUP_CONCAT(DISTINCT IF(eventtype=\'BRIDGE_ENTER\',
                                                  CONCAT_WS(\'\',
                                                            cid_num,
                                                            NULLIF(CONCAT(\'(\',cid_name,\')\'),
                                                                   CONCAT(\'(\',IF(cid_name,cid_num, \'\'),\')\')
                                                                  ),
                                                            NULLIF(NULLIF(cid_ani,SUBSTR(cid_num,2)),cid_num)
                                                            ),
                                                  NULL
                                                  )
                                     ORDER BY uniqueid SEPARATOR \'->\'
                                    ) as to_who,
                        CONCAT(DATE(eventtime),\'/\',cel.linkedid,\'.mp3\') AS record,
                        cel.linkedid
                FROM `cel`
                JOIN ( (SELECT DISTINCT linkedid
                        FROM cel
                        WHERE cid_num = \'' . $phone . '\'
                        AND eventtype =  \'BRIDGE_ENTER\' ' . $eventTime_limit . '
                        AND exten NOT REGEXP \'^\\\\*[0-9]$\'
	                    )
	                    UNION (SELECT DISTINCT linkedid
	                           FROM cel
	                           WHERE cid_ani = \'' . $phone . '\'
	                           AND eventtype =  \'BRIDGE_ENTER\' ' . $eventTime_limit . '
	                           AND exten NOT REGEXP \'^\\\\*[0-9]$\'
	                           )
	                 )
	            t ON cel.linkedid = t.`linkedid`
	            GROUP BY cel.linkedid
	            ORDER BY call_date';

        $res = Cel::findBySql($sql)
            ->asArray()
            ->all();

        if (isset($res[0])){

            return self::getSheet($res);

        } else {

            return [];

        }

    }


    /**
     * Получаем массив звонков
     *
     * @param $rows
     * @return array
     */
    private static function getSheet($rows)
    {
        foreach ($rows as $item => $row){

            if ( file_exists(RECORDS . $row['record']) ){

                $rows[$item]['record_src'] =  str_replace(WEB,'',RECORDS) . $row['record'];

            }

        }

        return $rows;
    }

}