<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

class Cdr extends ActiveRecord
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
        return 'cdr';
    }


    /**
     * Получаем статистику по 'Очереди'
     *
     * @param $post
     * @return array|string
     */
    public function getQueue($post)
    {
        if ( isset($post['ts_from']) AND isset($post['ts_to']) AND isset($post['queue'])){

            switch ($post){

                case ($post['ts_from'] === ''):
                    $message = 'Ошибка! Заполните поле с начальной датой.';
                    break;
                case ($post['ts_to'] === ''):
                    $message = 'Ошибка! Заполните поле с конечной датой.';
                    break;
                case ($post['queue'] === '-'):
                    $message = 'Ошибка! Выберите необходимую очередь.';
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

        $res = Cdr::find()

            ->select(
                '`disposition`,
                          UNIX_TIMESTAMP(`calldate`) as tsBegin,
                          UNIX_TIMESTAMP(`calldate`-`duration`) as tsAnswer,
                          UNIX_TIMESTAMP(`calldate`-(`billsec`+`duration`)) as tsEnd,
                          (`duration`) as ivWait,
                          (`billsec`) as ivTalk,
                          (`billsec`+`duration`) as ivDuration'
            )
            ->where('`calldate` BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')')
            ->andWhere('LENGTH(`src`)>5')
            ->andWhere('`lastapp` = \'Queue\'')
            ->andWhere('`lastdata` LIKE \'' . $post['queue'] . '%\'')
            ->orderBy('`calldate` ASC')
            ->asArray()
            ->all();

        if ( isset($res[0]) ){

            return self::getStat($res);

        } else {

            return [];

        }
    }

    /**
     * Получаем статистику по 'call-center'
     *
     * @param $post
     * @return array|string
     */
    public function getStatisticCallCenter($post)
    {
        if ( isset($post['ts_from']) AND isset($post['ts_to']) ){

            switch ($post){

                case ($post['ts_from'] === ''):
                    $message = 'Ошибка! Заполните поле с начальной датой.';
                    break;
                case ($post['ts_to'] === ''):
                    $message = 'Ошибка! Заполните поле с конечной датой.';
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

        $res = Cdr::find()
            ->select('
                          `disposition`,
                          UNIX_TIMESTAMP(`calldate`) as tsBegin,
                          UNIX_TIMESTAMP(`calldate`-`duration`) as tsAnswer,
                          UNIX_TIMESTAMP(`calldate`-(`billsec`+`duration`)) as tsEnd,
                          (`duration`) as ivWait,
                          (`billsec`) as ivTalk,
                          (`billsec`+`duration`) as ivDuration')
            ->where('`calldate` BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')')
            ->andWhere('LENGTH(`src`)>5')
            ->andWhere('`lastapp` = \'Queue\'')
            ->andWhere('`lastdata` LIKE \'1610%\' OR `lastdata` LIKE \'1611%\'')
            ->orderBy('`calldate` ASC')
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
     * @param $queue
     * @return array
     */
    private static function getStat($queue)
    {
        $group = [];

        foreach ($queue as $item => $value){

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
            if ( $value['disposition'] === 'ANSWERED' ){

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
            if ( ($value['disposition'] === 'BUSY') OR ($value['disposition'] === 'NO ANSWER') ){

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


        }

        return $group;
    }

    /**
     * Получаем данные о входящих вызовах
     *
     * @param $post
     * @return array|int|string
     */
    public function getInputCalls($post, $option = '')
    {
        $type = 'incoming';

        $rows_on_sheet = 50;
        $sheet = 1;

        $whe = '';
        $andOperator = '';
        $andQueue = '';
        $andPhone = '';

        if ( isset($post['ts_from']) AND isset($post['ts_to']) AND isset($post['queue']) AND isset($post['operator']) AND isset($post['phone']) AND isset($post['sheet']) ){

            if ( ($post['ts_from'] !== '') AND ($post['ts_to'] !== '') ){

                $ts_from = $post['ts_from'] . ' 00:00:00';
                $ts_to = $post['ts_to'] . ' 23:59:59';

                $ts_from = strtotime($ts_from);
                $ts_to = strtotime($ts_to);

            } else {

                $message = 'Ошибка! Выберите период.';

            }

            if ($_REQUEST['queue'] !== '-') {

                $andQueue = " AND queue_log.queuename=".intval($post['queue']);

            }

            if ( $post['operator'] !== '-' ) {

                $whe = ', cc.agents';
                $andOperator = " AND cc.agents.id=".intval($post['operator']);

            }

            if ( ($post['phone'] !== '') AND (is_numeric($post['phone'])) ){

                $andPhone = " AND cdr.src LIKE '%".$post['phone']."%'";

            }

            if ( ($post['sheet'] !== '') AND (is_numeric($post['sheet'])) ){

                $sheet = $post['sheet'];

            }

        } else {

            $message = 'Ошибка!';

        }

        if (isset($message)){

            return $message;

        }

        $sheets = self::getSheetsCount($type, $whe, $ts_from, $ts_to, $andQueue, $andOperator, $andPhone, $rows_on_sheet);

        if ($sheets < $sheet) {

            $sheet = 1;

        }

        if ($option === 'return sheets count'){

            return $sheets;

        }

        $limit_start = ($sheet-1)*$rows_on_sheet;

        $sql = 'SELECT DATE_FORMAT(calldate, \'%d.%m.%Y %H:%i:%s\') as calldate,
	                   DATE_FORMAT(calldate, \'%Y-%m-%d\') as rec_subdir,
	                   cdr.src, SUBSTRING_INDEX(queue_log.agent,\':\',1) as dst,
	                   SEC_TO_TIME(cdr.billsec) as billsec,
	                   cdr.uniqueid as uniqueid,
	                   queue_log.queuename as extqueue,
	                   cc.agents.id as idagent
	              FROM asteriskcdr.cdr, asteriskcdr.queue_log, cc.agents
	              WHERE calldate BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')
	              AND cdr.uniqueid=queue_log.callid
	              AND cdr.lastapp=\'Queue\' /*AND queue_log.agent LIKE \'1%:%\'*/
	              AND SUBSTRING_INDEX(queue_log.agent,\':\',-1) = cc.agents.id
	              AND (queue_log.event=\'COMPLETECALLER\' OR queue_log.event=\'COMPLETEAGENT\')
	              AND LENGTH(src) > 4 ' . $andQueue.$andOperator.$andPhone . '
	              GROUP BY calldate
	              ORDER BY calldate ASC LIMIT ' . $limit_start . ', ' . $rows_on_sheet . ' ';

        $res = Cdr::findBySql($sql)
            ->asArray()
            ->all();

//        debug($res,1);

        if (isset($res[0])){

            return self::getSheet($type, $res, $sheets, $sheet);

        } else {

            return [];

        }

    }

    /**
     * Получаем данные об исходящих вызовах
     *
     * @param $post
     * @param string $option
     * @return array|int|string
     */
    public function getOutgoingCalls($post, $option = '')
    {
        $type = 'outgoing';

        $rows_on_sheet = 50;
        $sheet = 1;

        $andPhone = '';

        if ( isset($post['ts_from']) AND isset($post['ts_to']) AND isset($post['phone']) AND isset($post['sheet']) ){

            if ( ($post['ts_from'] !== '') AND ($post['ts_to'] !== '') ){

                $ts_from = $post['ts_from'] . ' 00:00:00';
                $ts_to = $post['ts_to'] . ' 23:59:59';

                $ts_from = strtotime($ts_from);
                $ts_to = strtotime($ts_to);

            } else {

                $message = 'Ошибка! Выберите период.';

            }

            if ( ($post['phone'] !== '') AND (is_numeric($post['phone'])) ){

                $andPhone .= "AND dst LIKE '%".$post['phone']."%'";

            }

            if ( ($post['sheet'] !== '') AND (is_numeric($post['sheet'])) ){

                $sheet = $post['sheet'];

            }

        } else {

            $message = 'Ошибка!';

        }

        if (isset($message)){

            return $message;

        }

        $sheets = self::getSheetsCount($type, '', $ts_from, $ts_to, '', '', $andPhone, $rows_on_sheet);

        if ($sheets < $sheet) {

            $sheet = 1;

        }

        if ($option === 'return sheets count'){

            return $sheets;

        }

        $limit_start = ($sheet-1)*$rows_on_sheet;

        $sql = 'SELECT DATE_FORMAT(calldate, \'%d.%m.%Y %H:%i:%s\') as calldate,
                       DATE_FORMAT(calldate, \'%Y-%m-%d\') as rec_subdir,
                       channel as src,
                       dst,
                       (SEC_TO_TIME(billsec)) as billsec,
                       uniqueid
                FROM asteriskcdr.cdr
                WHERE channel NOT LIKE \'SIP/15%\'
                AND cdr.calldate BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')
                AND LENGTH(dst) > 5
                AND disposition = \'ANSWERED\'
                AND lastapp = \'Dial\' ' . $andPhone . '
                ORDER BY calldate
                LIMIT ' . $limit_start . ', ' . $rows_on_sheet . ' ';

        $res = Cdr::findBySql($sql)
            ->asArray()
            ->all();

        if (isset($res[0])){

            return self::getSheet($type, $res, $sheets, $sheet);

        } else {

            return [];

        }

    }

    /**
     * Получаем данные о звонках курьеров
     *
     * @param $post
     * @param string $option
     * @return array|int|string
     */
    public function getCouriersCalls($post, $option = '')
    {
        $type = 'couriers';

        $rows_on_sheet = 50;
        $sheet = 1;

        $andPhone = '';

        if ( isset($post['ts_from']) AND isset($post['ts_to']) AND isset($post['phone']) AND isset($post['sheet']) ){

            if ( ($post['ts_from'] !== '') AND ($post['ts_to'] !== '') ){

                $ts_from = $post['ts_from'] . ' 00:00:00';
                $ts_to = $post['ts_to'] . ' 23:59:59';

                $ts_from = strtotime($ts_from);
                $ts_to = strtotime($ts_to);

            } else {

                $message = 'Ошибка! Выберите период.';

            }

            if ( ($post['phone'] !== '') AND (is_numeric($post['phone'])) ){

                $andPhone .= "AND dst LIKE '%".$post['phone']."%'";

            }

            if ( ($post['sheet'] !== '') AND (is_numeric($post['sheet'])) ){

                $sheet = $post['sheet'];

            }

        } else {

            $message = 'Ошибка!';

        }

        if (isset($message)){

            return $message;

        }

        $sheets = self::getSheetsCount($type, '', $ts_from, $ts_to, '', '', $andPhone, $rows_on_sheet);

        if ($sheets < $sheet) {

            $sheet = 1;

        }

        if ($option === 'return sheets count'){

            return $sheets;

        }

        $limit_start = ($sheet-1)*$rows_on_sheet;

        $sql = 'SELECT DATE_FORMAT(calldate, \'%d.%m.%Y %H:%i:%s\') as calldate,
                       DATE_FORMAT(calldate, \'%Y-%m-%d\') as rec_subdir,
                       channel as src,
                       dst,
                       (SEC_TO_TIME(billsec)) as billsec,
                       uniqueid
                FROM asteriskcdr.cdr
                WHERE channel LIKE \'SIP/15%\'
                AND cdr.calldate BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')
                AND LENGTH(dst) > 4
                AND disposition = \'ANSWERED\'
                AND lastapp=\'Dial\' ' .$andPhone . '
                ORDER BY calldate
                LIMIT ' . $limit_start . ', ' . $rows_on_sheet . ' ';

        $res = Cdr::findBySql($sql)
            ->asArray()
            ->all();

        if (isset($res[0])){

            return self::getSheet($type, $res, $sheets, $sheet);

        } else {

            return [];

        }

    }

    /**
     * Получаем данные о входящих оптовых звонках
     *
     * @param $post
     * @param string $option
     * @return array|int|string
     */
    public function getInputOptCalls($post, $option = '')
    {
        $type = 'inputOpt';

        $rows_on_sheet = 50;
        $sheet = 1;

        $andOperator = '';
        $andPhone = '';

        if ( isset($post['ts_from']) AND isset($post['ts_to']) AND isset($post['operator']) AND isset($post['phone']) AND isset($post['sheet']) ){

            if ( ($post['ts_from'] !== '') AND ($post['ts_to'] !== '') ){

                $ts_from = $post['ts_from'] . ' 00:00:00';
                $ts_to = $post['ts_to'] . ' 23:59:59';

                $ts_from = strtotime($ts_from);
                $ts_to = strtotime($ts_to);

            } else {

                $message = 'Ошибка! Выберите период.';

            }

            if ( $post['operator'] !== '-' ) {

                $andOperator = " AND cdr.dstchannel = 'SIP/".intval($post['operator'])."' ";

            }

            if ( ($post['phone'] !== '') AND (is_numeric($post['phone'])) ){

                $andPhone = " AND cdr.src LIKE '%".$post['phone']."%'";

            }

            if ( ($post['sheet'] !== '') AND (is_numeric($post['sheet'])) ){

                $sheet = $post['sheet'];

            }

        } else {

            $message = 'Ошибка!';

        }

        if (isset($message)){

            return $message;

        }

        $sheets = self::getSheetsCount($type, '', $ts_from, $ts_to, '', $andOperator, $andPhone, $rows_on_sheet);

        if ($sheets < $sheet) {

            $sheet = 1;

        }

        if ($option === 'return sheets count'){

            return $sheets;

        }

        $limit_start = ($sheet-1)*$rows_on_sheet;

        $sql = 'SELECT DATE_FORMAT(`cdr`.`calldate`, \'%d.%m.%Y %H:%i:%s\') as `calldate`,
                       DATE_FORMAT(`cdr`.`calldate`, \'%Y-%m-%d\') as `rec_subdir`,
                       `cdr`.`src`,
                       `cdr`.`dst`,
                       SUBSTRING_INDEX(`cdr`.`dstchannel`,\'/\',-1) as `dstc`,
                       SEC_TO_TIME(`cdr`.`billsec`) as `billsec`,
                       `uniqueid` 
	            FROM `asteriskcdr`.`cdr`
	            WHERE `cdr`.`calldate` BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')
	            AND `cdr`.`lastapp`=\'Queue\'
	            AND (`cdr`.`lastdata`=\'1605\' OR `cdr`.`lastdata`=\'1607\')
	            AND `cdr`.`disposition`=\'ANSWERED\'' . $andOperator.$andPhone . '
	            GROUP BY `cdr`.`calldate`
	            ORDER BY `cdr`.`calldate` ASC
	            LIMIT ' . $limit_start . ', ' . $rows_on_sheet . ' ';

        $res = Cdr::findBySql($sql)
            ->asArray()
            ->all();

        if (isset($res[0])){

            return self::getSheet($type, $res, $sheets, $sheet);

        } else {

            return [];

        }
    }

    /**
     * Получаем количество страниц после разбивки по 50 строчек
     *
     * @param $type
     * @param $whe
     * @param $ts_from
     * @param $ts_to
     * @param $andQueue
     * @param $andOperator
     * @param $andPhone
     * @param $rows_on_sheet
     *
     * @return int
     */
    private static function getSheetsCount($type, $whe, $ts_from, $ts_to, $andQueue, $andOperator, $andPhone, $rows_on_sheet)
    {
        $rows_count = [];

        if ($type === 'incoming'){

            $rows_count = Cdr::find()
                ->select('COUNT(cdr.uniqueid) AS rows_count')
                ->from('asteriskcdr.queue_log, asteriskcdr.cdr ' . $whe . '')
                ->where('cdr.calldate BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')')
                ->andWhere('cdr.lastapp = \'Queue\'')
                ->andWhere('LENGTH(src) > 4')
                ->andWhere('queue_log.event=\'COMPLETECALLER\'')
                ->andWhere('cdr.uniqueid = queue_log.callid' . $andQueue.$andOperator.$andPhone . '')
                ->asArray()
                ->all();

        }

        if ($type === 'outgoing'){

            $rows_count = Cdr::find()
                ->select('COUNT(*) as rows_count')
                ->from('asteriskcdr.cdr')
                ->where('(src < 1500 OR src > 1599)')
                ->andWhere('cdr.calldate >= FROM_UNIXTIME(' . $ts_from . ')')
                ->andWhere('cdr.calldate <= FROM_UNIXTIME(' . $ts_to . ')')
                ->andWhere('LENGTH(dst) > 5')
                ->andWhere('disposition = \'ANSWERED\'')
                ->andWhere('lastapp=\'Dial\' ' . $andPhone . '')
                ->asArray()
                ->all();

        }

        if ($type === 'couriers'){

            $rows_count = Cdr::find()
                ->select('COUNT(cdr.uniqueid) as rows_count')
                ->from('asteriskcdr.cdr')
                ->where('`cdr`.`calldate` BETWEEN FROM_UNIXTIME(' . $ts_from . ') AND FROM_UNIXTIME(' . $ts_to . ')')
                ->andWhere('`cdr`.`lastapp`=\'Queue\'')
                ->andWhere('`cdr`.`dst`=\'1605\'')
                ->orderBy('`cdr`.`calldate`')
                ->asArray()
                ->all();

        }

        if ($type === 'inputOpt'){

            $rows_count = Cdr::find()
                ->select('COUNT(*) as rows_count')
                ->from('asteriskcdr.cdr')
                ->where('(src < 1500 OR src > 1599)')
                ->andWhere('cdr.calldate > FROM_UNIXTIME(' . $ts_from . ')')
                ->andWhere('cdr.calldate <= FROM_UNIXTIME(' . $ts_to . ')')
                ->andWhere('LENGTH(dst) > 4')
                ->andWhere('disposition = \'ANSWERED\'')
                ->andWhere('lastapp=\'Dial\'' . $andOperator.$andPhone . '')
                ->asArray()
                ->all();

        }

        if ( isset($rows_count[0])){

            $rows_count = $rows_count[0]['rows_count'];

        } else {

            $rows_count = 0;

        }


        $sheets = ceil($rows_count/$rows_on_sheet );

        return $sheets;
    }


    /**
     * Получаем массив звонков для выбранного листа(стр.)
     *
     * @param $type
     * @param $rows
     * @param $sheets
     * @param $sheet
     *
     * @return array
     */
    private static function getSheet($type, $rows, $sheets, $sheet)
    {
        if ($type === 'incoming'){

            $model = New Agents();
            $agents = $model->getAllFromAgents();

            foreach ($rows as $item => $row){

                if ( file_exists(RECORDS . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3') ){

                    $rows[$item]['record_src'] = str_replace(WEB,'',RECORDS) . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3';

                }

                foreach ($agents as $key => $agent){

                    if ($row['idagent'] === $agent['id']){
                        $rows[$item]['agentName'] = $agent['agent_name'];
                    }

                }

            }

        }

        if ($type === 'outgoing'){

            foreach ($rows as $item => $row){

                if ( file_exists(RECORDS . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3') ){

                    $rows[$item]['record_src'] = str_replace(WEB,'',RECORDS) . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3';

                }

            }

        }

        if ($type === 'couriers'){

            foreach ($rows as $item => $row){

                if ( file_exists(RECORDS . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3') ){

                    $rows[$item]['record_src'] = str_replace(WEB,'',RECORDS) . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3';

                }

            }

        }

        if ($type === 'inputOpt'){

            foreach ($rows as $item => $row){

                if ( file_exists(RECORDS . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3') ){

                    $rows[$item]['record_src'] = str_replace(WEB,'',RECORDS) . $row['rec_subdir'] . '/' . $row['uniqueid'] . '.mp3';

                }

            }

        }

        return [
            'rows' => $rows,
            'sheets' => $sheets,
            'sheet' => $sheet
        ];

    }

    /**
     * Получаем пропущенные вызовы за ночь
     *
     * @return array
     */
    public function getNightMissed()
    {
        $stop_date = date("Y-m-d", time());
        $start_date = time() - 86400;

        $start_date = date("Y-m-d", $start_date) . ' 23:01:59';
        $stop_date = $stop_date . ' 07:59:59';

        $callsNightMissed = Cdr::find()
            ->select('`calldate`, `clid`')
            ->where('`calldate` > \'' . $start_date . '\'')
            ->andWhere('`calldate` < \'' . $stop_date . '\'')
            ->andWhere('`channel` LIKE \'SIP/Beeline%\'')
            ->asArray()
            ->all();
        if (isset($callsNightMissed[0])){

            return $callsNightMissed;

        } else {

            return [];

        }

    }


}