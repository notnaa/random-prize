<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use yii\web\IdentityInterface;

/**
 * Class RegisterForm
 * @package app\models\forms
 */
class RegisterForm extends Model
{
    /** @var string */
    public $email;
    /** @var string */
    public $password;
    /** @var string */
    public $passwordRepeat;

    /** @var IdentityInterface|User|null */
    private $user = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password', 'passwordRepeat'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'Email has been existing.'],
            [['password', 'passwordRepeat'], 'string', 'min' => 6],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function register(): bool
    {
        if (!$this->hasErrors()) {
            $this->user = new User();
            $this->user->email = $this->email;
            $this->user->setPassword($this->password);

            if ($this->user->save()) {
                return true;
            }
        }

        return false;
    }
}
