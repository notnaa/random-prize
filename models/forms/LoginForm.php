<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\models\forms
 */
class LoginForm extends Model
{
    /** @var int */
    private const REMEMBER_DURATION = 3600 * 24 * 30;
    /** @var int */
    private const NOT_REMEMBER_DURATION = 0;

    /** @var string */
    public $email;
    /** @var string */
    public $password;
    /** @var bool */
    public $rememberMe = true;

    /** @var bool */
    private $user = false;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->getDurationAt());
        }

        return false;
    }

    /**
     * @return User|bool|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::findByEmail($this->email);
        }

        return $this->user;
    }

    /**
     * @return int
     */
    private function getDurationAt(): int
    {
        return $this->rememberMe ? self::REMEMBER_DURATION : self::NOT_REMEMBER_DURATION;
    }
}
