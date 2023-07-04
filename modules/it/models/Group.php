<?php

namespace app\modules\it\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Exception;

/**
 * Модель для работы с таблицей 'group'
 *
 * Class Group
 * @package app\modules\it\models
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
     * Получение всех групп пользователей из БД
     *
     * @return array
     */
    public function getGroups()
    {
        $groups = Group::find()
            ->asArray()
            ->all();

        if (isset($groups[0])){
            return $groups;
        }

        return [];
    }

    /**
     * Получение выбранных групп пользователей из БД
     *
     * @param $namesGroups array - массив имен выбранных групп
     * @return array
     */
    public function getGroupsByName($namesGroups)
    {
        $groups = Group::find()
            ->where(['in', 'name_group', $namesGroups])
            ->asArray()
            ->all();

        return $groups;
    }

    /**
     * Применение обновлений правил для групп в БД
     *
     * @param $strSet string - данные для правил
     * @param $strWhere string - данные для идентификации группы
     * @throws Exception
     * @return string - результат выполнения обновления БД
     */
    public function setRules($strSet, $strWhere)
    {
        $sql = 'UPDATE `group`
                    SET `rules` = CASE ' . $strSet . ' END
                    WHERE `id` IN (' . $strWhere . ')';

        $res = Yii::$app->db->createCommand($sql)->execute();

        if (isset($res)){

            return 'Права успешно изменены.';

        } else {

            return '';

        }

    }

}