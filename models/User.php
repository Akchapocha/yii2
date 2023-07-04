<?php

namespace app\models;

use app\controllers\AppController;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\web\IdentityInterface;

/**
 * Модель для работы с таблицей 'user' из БД
 *
 * Class User
 * @package app\models
 */
class User extends ActiveRecord implements IdentityInterface
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
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        return static::findOne(['access_token' => $token]);
    }

    /**
     * Поиск пользователя по uid(ldap)
     *
     * @param string $uid из ldap
     * @return static|null
     */
    public static function findByUid($uid)
    {
        return static::findOne(['uid' => $uid]);
    }

    /**
     * Добавление нового пользователя
     *
     * @param $uid
     * @param $username
     * @param $group
     */
    public static function createNewUser($uid, $username, $group)
    {
        $u = new User();
        $u->uid = $uid;
        $u->username = $username;
        $u->group = $group;
        $u->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }


    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function verifyPass($login)
    {
        $access = false;

        $user = User::find()
            ->where(['uid' => 'it-dept'])
            ->asArray()
            ->all();

        if (isset($user[0])){
            $access = password_verify($login['password'], $user['0']['password']);
        }

        return $access;
    }
}
