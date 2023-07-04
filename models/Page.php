<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Модель для работы с таблицей 'page' из БД
 *
 * Class Page
 * @package app\models
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

    /**
     * Получение 'id' страницы по её url
     *
     * @param $url
     * @return string
     */
    public function getPageIdByUrl($url)
    {
        $res = Page::find()
            ->where(['src_page' => $url])
            ->asArray()
            ->all();

        if (isset($res[0])){
            return $res[0]['id'];
        } else {
            return '';
        }
    }

    /**
     * Получение страниц из БД
     *
     * @param bool $drop - отбрасываем или нет, лишнии страницы
     * @return array|ActiveRecord[]
     */
    public function getAllPages($drop = false)
    {
        $pages = Page::find()
            ->asArray()
            ->orderBy('sort')
            ->all();

        foreach ($pages as $item => $page) {

            if ($page['src_page'] === '/login') {

                unset($pages[$item]);

            }

        }

        if ($drop === true) {

            $pages = $this->dropPages($pages);
        }

        return $pages;
    }

    /**
     * Получение 'title' для соответсвующей страницы
     *
     * @return string
     */
    public function getTitle()
    {
        $title = Page::find()
            ->asArray()
            ->where(['src_page' => '/' . Yii::$app->request->pathinfo])
            ->all();

        if (isset($title[0])){
            return $title[0]['title'];
        } else {
            return '';
        }
    }

    /**
     * Функция отбрасывания лишних (не допуступных) страниц
     *
     * @param $pages
     * @return mixed
     */
    private function dropPages($pages)
    {

        foreach ($pages as $item => $page){

            if (isset($_SESSION['rulesResult'])){

                $rulesResult = $_SESSION['rulesResult'];

                if ($rulesResult !== ''){

                    foreach ($rulesResult as $idPage => $rule){

                        if (!array_key_exists($page['id'], $rulesResult)){

                            unset($pages[$item]);

                        }

                        if ( ($page['id'] == $idPage) and ($rule === 'block') ){

                            unset($pages[$item]);

                        }

                    }

                } else {

                    return [$pages[0]];

                }

            }

        }

        return $pages;
    }


    /**
     * Получаем, отсортированный по порядку,
     * список страниц в категориях, отсортированных по порядку
     *
     * @return array
     */
    public function getPagesByGroup()
    {
//        debug('qwe',1);

        $categories = self::find()
            ->where('`parent` = 0')
            ->orderBy(['sort' => SORT_ASC])
            ->asArray()
            ->all();

        $pages = self::find()
            ->where('`parent` > 3')
            ->orderBy(['sort' => SORT_ASC])
            ->asArray()
            ->all();

        foreach ($categories as $itemCat => $category){

            foreach ($pages as $itemPage => $page){

                if ( $page['parent'] === $category['id'] ){

                    $categories[$itemCat]['pages'][$page['sort']] = $page;

                }

            }

        }

        if (isset($categories[0])){

            return $categories;

        } else {

            return [];

        }

    }

    /**
     * Получаем все страницы одного раздела
     *
     * @param $parentId
     * @return array
     */
    public function getPagesByParent($parentId)
    {
        $pages = self::find()
            ->where(['parent' => $parentId])
            ->orderBy(['sort' => SORT_ASC])
            ->asArray()
            ->all();

        if (isset($pages[0])){

            return $pages;

        } else {

            return [];

        }
    }

    /**
     * Отбрасываем не нужные пункты меню
     *
     * @param $pages
     * @param $dropPages
     * @return array
     */
    public function dropMenuButtons($pages, $dropPages)
    {
        if ( isset($pages[0]) AND $dropPages !== ''){

            $dropPages = explode(',', $dropPages);

            foreach ($pages as $item => $page){

                foreach ($dropPages as $key => $id){

                    if ($page['id'] === $id){

                        unset($pages[$item]);

                    }

                }

            }

        }

        return $pages;
    }
}