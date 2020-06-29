<?php

/**
* Авторизация взята из стандартной простой схемы, и переделана под авторизацию из базы.
*/

namespace app\models;
use app\models\Users;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    public static function findIdentity($id)
    {
        $Users = Users::find()->where(['id' => $id])->one();
		if ( !empty($Users) ) {
			
			$user['id'] = $Users->id;
			$user['username'] = $Users->username;
			$user['password'] = $Users->password;
			$user['authKey'] = $Users->authKey;
			$user['accessToken'] = $Users->accessToken;
			
			return new static($user);
        } else { return null; }
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $Users = Users::find()->where(['accessToken' => $token])->one();
		if ( !empty($Users) ) {
			
			$user['id'] = $Users->id;
			$user['username'] = $Users->username;
			$user['password'] = $Users->password;
			$user['authKey'] = $Users->authKey;
			$user['accessToken'] = $Users->accessToken;
			
			return new static($user);
        } else { return null; }
    }

    public static function findByUsername($username)
    {
		$Users = Users::find()->where(['username' => $username])->one();
		if ( !empty($Users) ) {
			
			$user['id'] = $Users->id;
			$user['username'] = $Users->username;
			$user['password'] = $Users->password;
			$user['authKey'] = $Users->authKey;
			$user['accessToken'] = $Users->accessToken;
			
			return new static($user);
        } else { return null; }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
