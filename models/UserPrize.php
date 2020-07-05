<?php

namespace app\models;

use app\services\prize\AbstractPrize;
use app\services\prize\PrizeFactoryInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserPrize
 * @package app\models
 *
 * @property int $id
 * @property string $entity_class
 * @property int $user_id
 * @property string $data
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class UserPrize extends ActiveRecord
{
    /** @var int */
    public const STATUS_WAIT = 1;
    /** @var int */
    public const STATUS_WAIT_DELIVERY = 2;
    /** @var int */
    public const STATUS_REFUSE = 3;
    /** @var int */
    public const STATUS_RECEIVED = 4;
    /** @var int */
    public const STATUS_ERROR = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_prizes}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['entity_class', 'user_id', 'data', 'status'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['status'], 'in', 'range' => [
                self::STATUS_WAIT,
                self::STATUS_WAIT_DELIVERY,
                self::STATUS_REFUSE,
                self::STATUS_RECEIVED,
                self::STATUS_ERROR,
            ]],
            [['entity_class', 'data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['entity_class', 'data'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return PrizeFactoryInterface|AbstractPrize
     */
    public function getEntity(): PrizeFactoryInterface
    {
        return new $this->entity_class($this->user);
    }
}
