<?php

namespace app\modules\it\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

class User extends ActiveRecord
{
    /**
     * Имя БД
     *
     * @return object|Connection|null
     * @throws InvalidConfigException
     */
    public static function getDb() {
        return Yii::$app->get('authopera');
    }

    /**
     * Имя таблицы в БД
     *
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Получаем искомых пользователей для результатов поиска
     *
     * @param $text string - данные из строки поиска пользователей
     * @return array
     */
    public function findUser($text)
    {
        $users = User::find()
            ->where(['or',
                ['like', 'uid', $text],
                ['like', 'username', $text]])
            ->asArray()
            ->all();

        if (isset($users[0])){
            return $users;
        } else {
            return [];
        }
    }

    /**
     * Получаем выбранного пользователя по 'id'
     *
     * @param $userId
     * @return array
     */
    public function getUsersById($userId)
    {

        $user = User::find()
            ->where(['id' => $userId])
            ->asArray()
            ->all();

        if (isset($user[0])){
            return $user[0];
        } else {
            return [];
        }

    }

    /**
     * Применение прав для пользователя
     *
     * @param $rulesUser
     * @return string
     */
    public function setRules($rulesUser)
    {

        $res = User::updateAll(['rules' => $rulesUser[key($rulesUser)]], ['id' => key($rulesUser)]);

        if (isset($res)){

            return 'Права успешно изменены.';

        } else {

            return '';

        }

    }
}