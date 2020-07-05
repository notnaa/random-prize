<?php

namespace app\models\forms\prize;

use app\models\User;
use app\models\UserPrize;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class AbstractPrizeForm
 * @package app\models\forms\prize
 */
abstract class AbstractPrizeForm extends Model implements PrizeFormInterface
{
    /** @var string */
    public $data;
    /** @var int */
    public $userId;

    /** @var UserPrize|null */
    protected $model;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['data', 'userId'], 'required'],
            [['data'], 'string'],
            [['userId'], 'integer'],
            [['userId'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @return User
     * @throws Exception
     */
    public function getUser(): User
    {
        $user = User::findIdentity($this->userId);

        if ($user === null) {
            throw new Exception(sprintf('User by #%d is not found.', $this->userId));
        }

        return $user;
    }

    /**
     * @return int
     */
    public function getCurrency(): int
    {
        return (int)ArrayHelper::getValue(json_decode($this->data, true), 'currency');
    }

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function loadUserPrize(UserPrize $userPrize): bool
    {
        $this->userId = $userPrize->user_id;
        $this->data = $userPrize->data;

        return true;
    }

    /**
     * @param UserPrize $userPrize
     */
    public function setModel(UserPrize $userPrize)
    {
        $this->model = $userPrize;
    }

    /**
     * @return UserPrize|null
     */
    public function getModel(): ?UserPrize
    {
        return $this->model;
    }
}
