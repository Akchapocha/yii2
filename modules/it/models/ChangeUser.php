<?php

namespace app\modules\it\models;

use yii\base\Model;

/**
 * Модель для обработки данных для смены прав пользователей
 *
 * Class ChangeUser
 * @package app\modules\it\models
 */
class ChangeUser extends Model
{
    /**
     * Получение ответа после смены правил для пользователей
     *
     * @param $post - $_POST данные со страницы it
     * @return string json
     */
    public function createMessage($post)
    {
        $message = 'Ошибка.';

        if ( (isset($post['usersId'])) and (isset($post['pages'])) ){

            if (count($post['usersId']) > 1){
                return json_encode('Ошибка.');
            }

            $users = $post['usersId'];
            $pages = $post['pages'];

            $rulesUsers = $this->getRulesUser($users);
            $rulesPages = $this->getIdPages($pages);

        }

        if ( (isset($rulesUsers)) and (isset($rulesPages)) ){

            $message = $this->setRulesUser($rulesUsers, $rulesPages);

        }

        return json_encode($message);
    }

    /**
     * Функция получения действующих правил для 'id' пользователя
     *
     * @param $user array id и имя пользователя
     * @return array
     */
    public function getRulesUser($user)
    {
        $model = New User();
        $userRules = $model->getUsersById(key($user));

        if ($userRules !== []){

            if ( !is_null($userRules['rules']) ){

                $userRules['rules'] = unserialize($userRules['rules']);

            } else {

                $userRules['rules'] = '';

            }

        }

        $idUserRules[key($user)] = $userRules['rules'];

        return $idUserRules;
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

            $namePage = preg_replace('/^user /','', $namePage);
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
     * Функция применения изменений в правилах для пользователей
     *
     * @param $rulesUser
     * @param $rulesPages
     * @return string
     */
    public function setRulesUser($rulesUser, $rulesPages)
    {
        $message = '';

        if ($rulesUser[key($rulesUser)] === ''){

            $rulesUser[key($rulesUser)] = serialize($rulesPages);

        } else {

            foreach ($rulesUser[key($rulesUser)] as $idPage => $ruleOld) {

                foreach ($rulesPages as $idP => $ruleNew){

                    if ( !array_key_exists($idP, $rulesUser[key($rulesUser)]) ){

                        $rulesUser[key($rulesUser)][$idP] = $ruleNew;

                    }

                    if ($idPage === $idP){

                        $rulesUser[key($rulesUser)][$idPage] = $ruleNew;

                    }

                }

            }

            $rulesUser[key($rulesUser)] = serialize($rulesUser[key($rulesUser)]);

        }

        if (isset($rulesUser)){
            $model = New User();
            $message = $model->setRules($rulesUser);
        }

        return $message;
    }

}