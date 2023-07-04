<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

class QueueLog extends ActiveRecord
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
        return 'queue_log';
    }

    /**
     * Получаем статистику по операторам
     *
     * @param $post
     * @return array|string
     */
    public function getQueueLog($post)
    {
        if ( isset($post['ts_from']) AND isset($post['ts_to']) AND isset($post['operator']) ){

            switch ($post){

                case ($post['ts_from'] === ''):
                    $message = 'Ошибка! Заполните поле с начальной датой.';
                    break;
                case ($post['ts_to'] === ''):
                    $message = 'Ошибка! Заполните поле с конечной датой.';
                    break;
                case ($post['operator'] === '-'):
                    $message = 'Ошибка! Выберите необходимого оператора.';
                    break;

            }

        } else {

            $message = 'Ошибка! Заполните необходимые поля.';

        }

        if (isset($message)){

            return $message;

        }

        $ts_from = $post['ts_from'] . ' 00:00:00';
        $ts_to = $post['ts_to'] . ' 23:59:59';

        $ts_from = strtotime($ts_from);
        $ts_to = strtotime($ts_to);

        $sql = 'SELECT `agent`, `event`, `uniqueid`, `callid`,
                      UNIX_TIMESTAMP(`calldate`) as tsBegin,
                      UNIX_TIMESTAMP(`calldate`-`duration`) as tsAnswer,
                      UNIX_TIMESTAMP(`calldate`-(`billsec`+`duration`)) as tsEnd,
                      (`duration`) as ivWait,
                      (`billsec`) as ivTalk,
                      (`billsec`+`duration`) as ivDuration,
                      (SELECT COUNT(`src`) as count
                            FROM `cdr`
                                JOIN (SELECT asteriskcdr.queue_log.agent
                                            FROM asteriskcdr.queue_log, cc.agents
                                            WHERE (SUBSTRING_INDEX(asteriskcdr.queue_log.agent, \':\', -1)) = cc.agents.id
                                            AND queue_log.agent LIKE \'1%:%\' \'' . $post['operator'] . '\'
                                            GROUP BY cc.agents.id ) as namereal
                                ON (SUBSTRING_INDEX(namereal.agent, \':\', 1)) = asteriskcdr.cdr.src
                            WHERE dcontext = \'DLPN_ccagent\' AND CHARACTER_LENGTH(dst) > 4
                            AND UNIX_TIMESTAMP(calldate) BETWEEN (\''.$ts_from.'\') AND (\''.$ts_to.'\')
                            AND lastapp = \'Dial\' ) as dial_out
                FROM asteriskcdr.queue_log, asteriskcdr.cdr
                WHERE time BETWEEN (\''.$ts_from.'\') AND (\''.$ts_to.'\') AND uniqueid=callid AND event != \'CONNECT\'
                AND queue_log.agent LIKE \'1%:%\' \'' . $post['operator'] . '\'
                ORDER BY time ASC';

       $res = QueueLog::findBySql($sql)
            ->asArray()
            ->all();

       if ( isset($res[0]) ){

           return self::getStat($res);

       } else {

           return [];

       }

    }

    /**
     * Получаем массив статистики разбитый по дням для 'Очереди' или 'call-center'
     *
     * @param $queueLog
     * @return array
     */
    private static function getStat($queueLog)
    {
        $group = [];

        foreach ($queueLog as $item => $value){

            $date = date('d.m.Y', $value['tsBegin']);
            $group[$date][] = $value;

            /**Данные для всех вызовов*/
            if ( !isset($group[$date]['allCalls']) ){

                $group[$date]['allCalls'] =  1;

            } else {

                $group[$date]['allCalls'] = $group[$date]['allCalls'] + 1;

            }

            if ( !isset($group[$date]['callsDuration']) ){

                $group[$date]['callsDuration'] =  $value['ivDuration'];

            } else {

                $group[$date]['callsDuration'] = $group[$date]['callsDuration'] + $value['ivDuration'];

            }

            /**Данные для принятых вызовов*/
            if ( ($value['event'] === 'COMPLETEAGENT') OR ($value['event'] === 'COMPLETECALLER') ){

                if ( !isset($group[$date]['allAnsweredCalls']) ){

                    $group[$date]['allAnsweredCalls'] =  1;

                } else {

                    $group[$date]['allAnsweredCalls'] = $group[$date]['allAnsweredCalls'] + 1;

                }

                if ( !isset($group[$date]['waitAnsweredCalls'])){

                    $group[$date]['waitAnsweredCalls'] = $value['ivWait'];

                } else {

                    $group[$date]['waitAnsweredCalls'] = $group[$date]['waitAnsweredCalls'] + $value['ivWait'];

                }

                if ( !isset($group[$date]['talkCalls'])){

                    $group[$date]['talkCalls'] = $value['ivTalk'];

                } else {

                    $group[$date]['talkCalls'] = $group[$date]['talkCalls'] + $value['ivTalk'];

                }

            }

            /**Данные для пропущенных вызовов*/
            if ( ($value['event'] === 'RINGNOANSWER') ){

                if ( !isset($group[$date]['allMissedCalls']) ){

                    $group[$date]['allMissedCalls'] =  1;

                } else {

                    $group[$date]['allMissedCalls'] = $group[$date]['allMissedCalls'] + 1;

                }

                if ( !isset($group[$date]['waitMissedCalls'])){

                    $group[$date]['waitMissedCalls'] = $value['ivWait'];

                } else {

                    $group[$date]['waitMissedCalls'] = $group[$date]['waitMissedCalls'] + $value['ivWait'];

                }

            }

//            debug($value);

            /**Данные для исходящих вызовов*/
            if ( intval($value['dial_out']) > 0 ){

                if ( !isset($group[$date]['allDial_out']) ){

                    $group[$date]['allDial_out'] =  1;

                } else {

                    $group[$date]['allDial_out'] = $group[$date]['allDial_out'] + 1;

                }

            }

        }

        return $group;
    }
}