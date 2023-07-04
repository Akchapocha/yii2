<?php

namespace app\components\NavigationWidget;
use Yii;
use yii\base\Widget;
use app\controllers\AppController;

/**
 * Class NavigationWidget
 *
 * Виджет доступных страниц
 *
 * @package app\components\NavigationWidget
 */
class NavigationWidget extends Widget
{
    /**
     * Экшн для виджета
     *
     * @return string
     */
    public function run()
    {
        $pages = AppController::getPages(true);
        $pages = self::dropPages($pages);

        return $this->render('navigationView', compact('pages'));
    }

    /**
     * Оставляем только необходимые страницы для отображения на вкладках
     *
     * @param $pages
     * @return array
     */
    private static function dropPages($pages)
    {
        foreach ($pages as $item => $page){

            if ( ($page['src_page'] === Yii::$app->request->url) AND (intval($page['parent']) !== 0) ){

                if ( intval($page['cat']) === 1 ){

                    $parentId = $page['id'];

                } else {

                    $parentId = $page['parent'];

                }

            }

        }

        foreach ($pages as $item => $page){

            if (isset($parentId)){

                if ($parentId === $page['id']){

                    $res[$item] = $page;

                }

            }

            if ( ($page['src_page'] === '/') OR ($page['src_page'] === '/it') ){

                $res[$item] = $page;

            }

        }

//        debug($res);

        return $res;
    }
}