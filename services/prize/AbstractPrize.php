<?php

namespace app\services\prize;

use app\models\forms\prize\PrizeFormInterface;
use app\models\User;
use app\models\UserPrize;
use app\services\reward\AbstractRewardService;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class AbstractPrizeEntity
 * @package app\services\prize
 */
abstract class AbstractPrize extends BaseObject implements PrizeFactoryInterface
{
    /** @var int */
    public const CURRENCY_MONEY = 1;
    /** @var int */
    public const CURRENCY_LOYALTY_POINT = 2;
    /** @var int */
    public const CURRENCY_GIFT = 3;
    /** @var string */
    public const CURRENCY_ATTRIBUTE_NAME = 'currency';

    /** @var string */
    protected const LIMIT_ATTRIBUTE_NAME = 'limit';

    /** @var User */
    protected $user;
    /** @var array */
    protected $data;

    /**
     * AbstractPrize constructor.
     * @param User $user
     * @param array $config
     */
    public function __construct(User $user, $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
    }

    /**
     * @return string|PrizeFormInterface
     */
    abstract public function getForm(): string;

    /**
     * @return string|AbstractRewardService
     */
    abstract protected function getRewardService(): string;

    /**
     * @return array
     */
    abstract protected function getConfig(): array;

    /**
     * @return array
     */
    abstract protected function getData(): array;

    /**
     * @return PrizeFormInterface|null
     * @throws Exception
     */
    public function playOut(): ?PrizeFormInterface
    {
        $limit = $this->getConfig()[self::LIMIT_ATTRIBUTE_NAME];

        if ($limit !== null && $this->checkLimit() >= $limit) {
            return null;
        }

        $formClassName = $this->getForm();
        /** @var PrizeFormInterface|Model $form */
        $form = new $formClassName();
        $data = [
            'data' => json_encode($this->getData()),
            'userId' => (int)$this->user->getId()
        ];

        if (!$form->load($data, '') || !$form->validate() || $form->hasErrors()) {
            return $form;
        }

        if (!$this->save($form)) {
            throw new Exception(sprintf('Error play out prize. Please, try again later.'));
        }

        return $form;
    }

    /**
     * @param UserPrize $userPrize
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function reward(UserPrize $userPrize): bool
    {
        /** @var AbstractRewardService $service */
        $service = \Yii::$container->get($this->getRewardService());
        return $service->charge($userPrize);
    }

    /**
     * @param UserPrize $userPrize
     * @return int|null
     */
    public function convert(UserPrize $userPrize): ?int
    {
        return null;
    }

    /**
     * @param PrizeFormInterface $form
     * @return bool
     */
    protected function save(PrizeFormInterface $form): bool
    {
        $model = new UserPrize();
        $model->entity_class = static::class;
        $model->user_id = (int)$form->getUser()->getId();
        $model->data = json_encode($form->getData());
        $model->status = UserPrize::STATUS_WAIT;
        $result = $model->save();
        $form->setModel($model);

        return $result;
    }

    /**
     * @return int
     */
    private function checkLimit(): int
    {
        return (int)UserPrize::find()
            ->andWhere(['user_id' => (int)$this->user->getId()])
            ->andWhere(['entity_class' => static::class])
            ->count();
    }
}
