<?php

namespace app\modules\it\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Модель работы с таблицей 'page'
 *
 * Class Page
 * @package app\modules\it\models
 */
class Page extends ActiveRecord
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
        return 'page';
    }

    public function getAllPages()
    {
        $pages = Page::find()
            ->where('`parent` > 0 AND `id` <> 2' )
            ->asArray()
            ->all();

        return $pages;
    }


    /**
     *Получаем 'id' страниц
     *
     * @param $namesPages
     * @return array
     */
    public function getIdPages($namesPages)
    {
        $idPages = Page::find()
            ->select(['id', 'name_button'])
            ->where(['in', 'name_button', $namesPages])
            ->asArray()
            ->all();

        $res = [];

        if ($idPages !== []){

            foreach ($idPages as $item => $id){
                $res[$id['name_button']] = $id['id'];
            }

        }

        if ($res !== []){
            return $res;
        }

        return [];
    }

}