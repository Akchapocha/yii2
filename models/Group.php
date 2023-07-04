<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Модель для работы с таблицей 'group' из БД
 *
 * Class Group
 * @package app\models
 */
class Group extends ActiveRecord
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
        return 'group';
    }

    /**
     * Получаем правила для группы аутентифицированного пользователя
     *
     * @param $groupName - имя группы пользователя
     * @return array|string - возвращаем массив правил, если они назначены
     */
    public function getGroupRules($groupName)
    {
        $res = Group::find()
            ->where(['name_group' => $groupName])
            ->asArray()
            ->all();

        if (isset($res[0])){

            if ( !is_null($res[0]['rules']) ){

                $rulesGroup = unserialize($res[0]['rules']);

            } else {

                $rulesGroup = '';

            }

        } else {

            $rulesGroup = '';

        }

        return $rulesGroup;
    }

}