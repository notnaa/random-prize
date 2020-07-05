<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property int $loyalty_point
 * @property string $created_at
 * @property string $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password', 'auth_key'], 'string'],
            [['loyalty_point'], 'integer', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'password'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            [['email'], 'unique', 'on' => self::SCENARIO_DEFAULT],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?User
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * @param string $token
     * @param null $type
     * @return User|null
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        throw new NotSupportedException();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public static function findByEmail(string $email): ?User
    {
        return static::find()
            ->where(['email' => $email])
            ->limit(1)
            ->one();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    /**
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword(string $password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return string|null
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
