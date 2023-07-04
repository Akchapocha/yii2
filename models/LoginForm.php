<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * Модель формы авторизации
 *
 * @property User|null $user This property is read-only.
 */
class LoginForm extends Model
{
    /**Поля формы*/
    public $username;
    public $password;
    public $rememberMe = true;

    /**Значения из LDAP*/
    public $displayName;
    public $groupName;

    /**
     * Правила для полей формы
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Переименовываем подписи к полям
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }

    /**
     * Обязательный метод для объявления в YII2. Не используем.
     *
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {

    }

    /**
     * Аутентификация пользователя в приложении
     *
     * @return bool
     */
    public function login()
    {
        /**Получаем данные из формы*/
        $reqLogin = Yii::$app->request->post();
        $ldap_bind = [];

        if ($reqLogin){

            if (isset($reqLogin['LoginForm'])){

                /**------------------------------Можно не использовать-----------------------------------------------*/
                if ( $reqLogin['LoginForm']['username'] === 'it-dept' ){
                    $model = New User();
                    $access = $model->verifyPass($reqLogin['LoginForm']);

                    if ($access === true){

                        $this->displayName = 'it-dept';
                        $this->groupName = 'Domain Users';

                        if ($this->validate()) {

                            $res = $this->getUser();

                            if ($this->rememberMe) {

                                $u = $res;
                                $u->generateAuthKey();
                                $u->save();

                            }

                            return Yii::$app->user->login($res, $this->rememberMe ? 3600*24*30 : 0);
                        }

                    }

                }
                /**---------------------------------------------------------------------------------------------------*/


                /**Пробуем подключиться к LDAP с логин/паролем из формы*/
                $ldap_bind = self::bindLdap($reqLogin['LoginForm']['username'], $reqLogin['LoginForm']['password']);

            }

        }

        if ($ldap_bind !== []){

            if (!isset($ldap_bind['err'])){

                $this->displayName = $ldap_bind['displayName'];
                $this->groupName = $ldap_bind['groupName'];

                if ($this->validate()) {

                    $res = $this->getUser();

                        if ($this->rememberMe) {

                            $u = $res;
                            $u->generateAuthKey();
                            $u->save();

                        }

                    return Yii::$app->user->login($res, $this->rememberMe ? 3600*24*30 : 0);
                }

            }

        }

        return false;
    }

    /**
     * Поиск пользователя в БД по uid(ldap)
     *
     * @return User|null
     */
    public function getUser()
    {
        return User::findByUid($this->username);
    }

    /**
     * Функция проверки пользователей в LDAP.
     * Если пользователь аутентифицировался в LDAP, но его нет в БД, то добавляем запись в БД
     *
     * @param $username - из формы
     * @param $password - из формы
     * @return array
     */
    private function bindLdap($username, $password)
    {
        $hostname = LDAP['hostname'];
        $base_dn = LDAP['base_dn'];
        $user_dn = 'userid='.$username.',ou=people,'.$base_dn;

        $ldap_conn = @ldap_connect($hostname);

        if ($ldap_conn){

            $ldap_bind = @ldap_bind($ldap_conn, $user_dn, $password);

            if ($ldap_bind){

                $filter = '(uid='.$username.')';
                $ldap_search = ldap_search($ldap_conn, $base_dn, $filter, array('uid', 'sambaprimarygroupsid'));
                $entries = ldap_get_entries($ldap_conn, $ldap_search);

                $reqLdap['login'] = $entries[0]['uid'][0];

                $groupSID = $entries[0]['sambaprimarygroupsid'][0];
                $filter = '(&(objectClass=posixGroup)(sambasid='.$groupSID.'))';
                $ldap_search = ldap_search($ldap_conn, $base_dn, $filter);
                $entries = ldap_get_entries($ldap_conn, $ldap_search);

                $reqLdap['groupName'] = $entries[0]['cn'][0];

                $filter = '(uid='.$username.')';
                $ldap_search = ldap_search($ldap_conn, $base_dn, $filter);
                $entries = ldap_get_entries($ldap_conn, $ldap_search);

                $reqLdap['displayName'] = $entries[0]['cn'][0];

            } else {

                $reqLdap['err'][] = 'Такого пользователя не существует.';
            }

        } else {

            $reqLdap['err'][] = 'Нет соединения с БД';
        }

        if (!isset($reqLdap['err'])){

            if (!$this->getUser()){
                /**Добавляем пользователя в БД, если такого пользователя там нет*/
                User::createNewUser($reqLdap['login'], $reqLdap['displayName'], $reqLdap['groupName']);

            }

        }


        return $reqLdap;
    }
}
