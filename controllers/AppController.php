<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Page;
use app\models\Group;

class AppController extends Controller
{

    /**
     * Функция для получения данных о страницах
     *
     * @param bool $drop - если false, то получаем список всех страниц,
     *                     если true, то получаем список страниц, который доступен для просмотра данным пользователем
     *
     * @return array
     */
    public static function getPages($drop = false)
    {
        $model = new Page();
        $pages = $model->getAllPages($drop);

        return $pages;
    }

    /**
     * Функция получения 'title'
     *
     * @return string
     */
    protected static function getTitle()
    {
        $model = new Page();

        return $model->getTitle();
    }

    /**
     * Функция переадрессации с заблокированных для пользователя страниц
     */
    protected static function accessCheck()
    {
        $identity = Yii::$app->user->identity;

        if ( ($identity) AND (!isset($_SESSION['rulesResult'])) ){
            self::setRules($identity);
        }

        if (Yii::$app->user->isGuest === true){

            Yii::$app->getResponse()->redirect('/login');

        } else {

            $access = self::getAccess();

            if ($access === 'denied'){

                Yii::$app->getResponse()->redirect('/403');

            }

        }
    }

    /**
     * Функция проверки доступа к странице
     *
     * @return string
     */
    protected static function getAccess()
    {
        if (isset($_SESSION['rulesResult'])){
            $rulesResult = $_SESSION['rulesResult'];

            $model = New Page();
            $pageId = $model->getPageIdByUrl(Yii::$app->request->url);

            if ($pageId !== ''){

                if ($rulesResult !== ''){

                    if ( (array_key_exists($pageId, $rulesResult)) and ($rulesResult[$pageId] === 'read') ){

                        $access = 'is allowed';

                    } else {

                        $access = 'denied';

                    }

                } else {

                    if (intval($pageId) === 1){

                        $access = 'is allowed';

                    } else {

                        $access = 'denied';

                    }


                }



            } else {

                $access = 'denied';

            }

        } else {

            $access = 'denied';

        }

        return $access;
    }

    /**
     * Функция установки правил в сесию
     *
     * @param $identity
     */
    protected static function setRules($identity)
    {
        $model = New Group();
        $rulesGroup = $model->getGroupRules($identity->group);

        $rulesUser = $identity->rules;
        if ( !is_null($rulesUser) ){

            $rulesUser = unserialize($rulesUser);

        } else {

            $rulesUser = '';

        }

        if (isset( $_SESSION['rulesResult']) ){

            unset($_SESSION['rulesResult']);

        }

        $_SESSION['rulesResult'] = self::getRulesResult($rulesGroup, $rulesUser);
    }

    /**
     * Функция получения прав пользователя на основе прав группы и прав самого пользователя
     *
     * @param $rulesGroup - права группы
     * @param $rulesUser - права пользователя
     * @return string|array - массив прав, если есть хоть одно правило для групп или пользователя
     */
    private static function getRulesResult($rulesGroup, $rulesUser)
    {

        $rules['rulesGroup'] = $rulesGroup;
        $rules['rulesUser'] = $rulesUser;

        switch ($rules){

            case ( ($rules['rulesGroup'] === '') and ($rules['rulesUser'] === '') ):

                $rulesResult = '';

                break;

            case ( ($rules['rulesGroup'] !== '') and ($rules['rulesUser'] === '') ):

                $rulesResult = $rulesGroup;

                break;

            case ( ($rules['rulesGroup'] === '') and ($rules['rulesUser'] !== '') ):

                $rulesResult = $rulesUser;

                break;

            case ( ($rules['rulesGroup'] !== '') and ($rules['rulesUser'] !== '') ):

                foreach ($rulesGroup as $idPageFromGroup => $ruleGroup){

                    foreach ($rulesUser as $idPageFromUser => $ruleUser){

                        if( !array_key_exists($idPageFromGroup,$rulesUser) ){

                            $rulesResult[$idPageFromGroup] = $ruleGroup;

                        }

                        if ( !array_key_exists($idPageFromUser, $rulesGroup) ){

                            $rulesResult[$idPageFromUser] = $ruleUser;

                        }

                        if ($idPageFromGroup === $idPageFromUser){

                            $rulesResult[$idPageFromUser] = $ruleUser;

                        }

                    }

                }

                break;

        }

        if (is_array($rulesResult)){
            ksort($rulesResult);
        }

        return $rulesResult;

    }
}