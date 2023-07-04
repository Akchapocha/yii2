<?php

namespace app\models;

use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Connection;
use yii\helpers\Json;

class Agents extends ActiveRecord
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
        return 'agents';
    }

    /**
     * Получаем список свободных "pin", либо один из свободных
     *
     * @param $action - определяет, что будем возвращать: список свободных "pin", либо один из свободных
     * @return array|string список свободных "pin" (начиная с "1000" и заканчивая "9999") | один из свободных, либо пустой массив
     */
    public function getPins($action)
    {
        $res = Agents::find()
            ->select('`pin`')
            ->where('`pin` >= 1000')
            ->andWhere('`pin` <= 9999')
            ->orderBy('`pin`')
            ->asArray()
            ->all();

        if (isset($res[0])){

            return $this->getAvailablePins($res, $action);

        } else {

            return [];

        }

    }

    /**
     * Получаем список всех (не удаленных) операторов
     *
     * @return array массив операторов или пустой массив
     */
    public function getAllFromAgents()
    {
        $res = Agents::find()
            ->orderBy('agent_name')
            ->asArray()
            ->all();

        if (isset($res[0])){
            return $res;
        } else {
            return [];
        }
    }

    /**
     * Получаем данные оператора по id
     *
     * @param $id
     * @return array|string
     */
    public function getAgentById($id){

        $res = Agents::find()
            ->where('`id` = ' . $id . '')
            ->asArray()
            ->all();

        if (isset($res[0])){

            return $res[0];

        } else {

            return 'Ошибка. Такой оператор не найден.';

        }

    }

    /**
     * Сохранение отредактированного оператора
     *
     * @param $post
     * @return string
     */
    public function saveOperator($post)
    {
        $message = '';

        if ( isset($post['id']) AND isset($post['fio']) AND isset($post['pin']) AND isset($post['queue1']) AND isset($post['queue2']) AND isset($post['queue3']) ){

            if ($post['fio'] === ''){

                $message = 'Заполните ФИО.';

            }

            $res = Agents::find()
                ->where('`pin` = \'' . $post['pin'] . '\'')
                ->asArray()
                ->all();

            if ( isset($res[0]) ){

                if ($res[0]['id'] !== $post['id']){

                    $message = 'Такой пин уже назначен для ' . $res[0]['agent_name']. '';

                }

            }

        } else {

            return 'Ошибка. Не удалось сохранить отредактированного оператора.';

        }

        if ($message !== ''){

            return $message;

        }

        $res = Agents::updateAll(
            [
            'agent_name' => $post['fio'],
            'pin' => $post['pin'],
            'q1' => $post['queue1'],
            'q2' => $post['queue2'],
            'q3' => $post['queue3']
            ],

            ['id' => $post['id']]);

        if (isset($res)){

            $message = 'Оператор был успешно изменен.';

        } else {

            $message = 'Ошибка. Не удалось изменить оператора.';

        }

        return $message;
    }

    /**
     * Получаем список видимых (не скрытых) операторов
     *
     * @return array массив операторов отсортированный по имени или пустой массив
     */
    public function getVisibleAgents()
    {
        $res = Agents::find()
            ->where(['hidden' => 0])
            ->orderBy('agent_name')
            ->asArray()
            ->all();

        if (isset($res[0])){
            return $res;
        } else {
            return [];
        }

    }

    /**
     * Получаем список скрытых операторов
     *
     * @return array массив операторов отсортированный по имени или пустой массив
     */
    public function getHiddenAgents()
    {
        $res = Agents::find()
            ->where(['hidden' => 1])
            ->orderBy('agent_name')
            ->asArray()
            ->all();

        if (isset($res[0])){
            return $res;
        } else {
            return [];
        }

    }

    /**
     * Скрываем выбраноого пользователя
     *
     * @param $id
     * @return Json
     */
    public function hideOperator($id)
    {
        $res = Agents::updateAll( ['hidden' => 1], ['id' => $id]);

        if($res){
            return json_encode('Оператор был успешно скрыт.');
        } else {
            return json_encode('Не удалось скрыть оператора.');
        }
    }

    /**
     * Добавляем нового оператора
     *
     * @param $post
     * @return Json
     * @throws Throwable
     */
    public function createOperator($post)
    {
        $pin = Agents::find()
            ->where(['pin' => $post['pin']])
            ->asArray()
            ->all();

        if (isset($pin[0])){

            return json_encode('Такой ПИН уже назначен для ' . $pin[0]['agent_name'] . '.');

        } else {

            $model = New Agents();
            $model->agent_name = $post['fio'];
            $model->pin = $post['pin'];
            $model->q1 = $post['queue1'];
            $model->q2 = $post['queue2'];
            $model->q3 = $post['queue3'];
            $model->hidden = 0;
            $res = $model->insert();

        }

        if ($res){

            return json_encode('Оператор был успешно добавлен.');

        } else {

            return json_encode('Не удалось добавить оператора.');
        }
    }

    /**
     * Удаление оператора
     *
     * @param $id
     * @return false|string
     */
    public function deleteOperator($id)
    {
        $res = Agents::deleteAll(['id' => $id]);

        if ($res){
            return json_encode('Оператор был успешно удален.');
        } else {
            return json_encode('Не удалось удалить оператора.');
        }
    }

    /**
     * Получаем очередь
     *
     * @return array
     */
    public function getQueue()
    {
        $queue = [];

        $res = Agents::find()
            ->select('`q1`, `q2`, `q3`')
            ->asArray()
            ->all();

        if (isset($res['0'])){

            foreach ($res as $item => $q){

                foreach ($q as $key => $value){

                    if ( $value !== ''){

                        $queue[$value] = $value;

                    }

                }

            }

        }

        return $queue;

    }

    /**
     * Получаем список или один из списка доступных 'pin'
     *
     * @param $pins - массив занятых 'pin'
     * @param $action - определяет, что будем возвращать: список или один из списка доступных 'pin'
     * @return array|string - список | один из списка доступных 'pin'
     */
    private function getAvailablePins($pins, $action)
    {
        $busyPins = [];
        $allPins = [];
        $availablePins = [];

        foreach ($pins as $item => $pin){

            $busyPins[] = $pin['pin'];

        }

        if ($busyPins !== []){

            for ($i = 1000; $i <= 9999; $i++){

                $allPins[] = $i;

            }

            $availablePins = array_diff($allPins, $busyPins);

        }

        $idRandomPin = array_rand($availablePins,1);

        if ( $action === 'getRandomPin' ){

            return $availablePins[$idRandomPin];
        }

        if ( $action === 'getAvailablePins' ){

            return $availablePins;

        }

    }


}