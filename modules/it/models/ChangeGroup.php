<?php

namespace app\modules\it\models;

use yii\base\Model;
use yii\db\Exception;

/**
 * Модель для обработки данных для смены прав для групп пользователей
 *
 * Class ChangeGroup
 * @package app\modules\it\models
 */
class ChangeGroup extends Model
{
    /**
     * Получение ответа после смены правил для групп пользователей
     *
     * @param $post - $_POST данные со страницы it
     * @throws Exception
     * @return string json
     */
    public function createMessage($post)
    {
        $message = 'Ошибка.';

        if ( (isset($post['groups'])) and (isset($post['pages'])) ){

            $groups = $post['groups'];
            $pages = $post['pages'];

            $rulesGroups = $this->getRulesGroups($groups);
            $rulesPages = $this->getIdPages($pages);

        }

        if ( (isset($rulesGroups)) and (isset($rulesPages)) ){

            $message = $this->setRulesGroup($rulesGroups, $rulesPages);

        }

        return json_encode($message);
    }

    /**
     * Функция получения правил для выбранных групп ('id') пользователей
     *
     * @param $namesGroups array - имена выбранных групп
     * @return array
     */
    private function getRulesGroups($namesGroups)
    {
        $model = New Group();
        $groups = $model->getGroupsByName($namesGroups);

        $idGroupRules = [];

        foreach ($groups as $item => $group){

            if (  !is_null($group['rules']) ){

                $group['rules'] = unserialize($group['rules']);

            } else {

                $group['rules'] = '';
            }

            $idGroupRules[$group['id']] = $group['rules'];
        }

        return $idGroupRules;
    }

    /**
     * Функция получения правил для 'id' страниц
     *
     * @param $arr array - массив с 'грязными' ключами - названиями страниц
     * @return array
     */
    private function getIdPages($arr)
    {
        foreach ($arr as $namePage => $val){
            $namePage = preg_replace('/^group /','', $namePage);
            $namePages[$namePage] = $val;
        }

        $res = [];
        $idPagesRule = [];

        foreach ($namePages as $namePage => $rule){

            $res[] = $namePage;

        }

        $model = New Page();
        $idPages = $model->getIdPages($res);

        foreach ($idPages as $pageName => $idPage){

            foreach ($namePages as $namePage => $rule){

                if ($pageName === $namePage) {

                    $idPagesRule[$idPage] = $rule;

                }

            }

        }

        return $idPagesRule;
    }

    /**
     * Функция применения изменений в правилах для групп пользователей
     *
     * @param $rulesGroups array - существующие правила для групп
     * @param $rulesPages array - новые правила для групп
     * @throws Exception
     * @return string
     */
    private function setRulesGroup($rulesGroups, $rulesPages)
    {
        $message = '';

        foreach ($rulesGroups as $idGroup => $rulesGroup) {

            if ($rulesGroup === ''){

                $rulesGroups[$idGroup] = serialize($rulesPages);

            } else {

                foreach ($rulesGroup as $idPage => $ruleOld) {

                    foreach ($rulesPages as $idP => $ruleNew){

                        if ( !array_key_exists($idP, $rulesGroup) ){

                            $rulesGroups[$idGroup][$idP] = $ruleNew;

                        }

                        if ($idPage === $idP){

                            $rulesGroups[$idGroup][$idPage] = $ruleNew;

                        }

                    }

                }

                $rulesGroups[$idGroup] = serialize($rulesGroups[$idGroup]);

            }

        }

        $strSet = '';
        $strWhere = '';

        if (isset($rulesGroups)){

            foreach ($rulesGroups as $idGroup => $rules){

                $strSet = $strSet . 'WHEN `id` = ' . $idGroup . ' THEN \'' . $rules . '\' ';
                $strWhere = $strWhere . $idGroup . ', ';

            }

            $strSet = preg_replace('/ $/', '', $strSet);
            $strWhere = preg_replace('/, $/', '', $strWhere);

            $model = New Group();
            $message = $model->setRules($strSet, $strWhere);

        }

        return $message;
    }
}