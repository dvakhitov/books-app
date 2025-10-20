<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

final class User extends ActiveRecord implements IdentityInterface
{
    public int $id;
    public string $username;
    public string $password;
    public string $authKey {
        get {
            return $this->authKey;
        }
    }
    public string $accessToken;

    private static array $users = [
        '100' => [
            'id'          => '100',
            'username'    => 'admin',
            'password'    => 'admin',
            'authKey'     => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id'          => '101',
            'username'    => 'demo',
            'password'    => 'demo',
            'authKey'     => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): IdentityInterface|static|null
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface|static|null
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): null|static
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return $this->password === $password;
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }
}
