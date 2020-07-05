<?php

namespace app\services\prize;

use app\models\forms\prize\GiftPrizeForm;
use app\models\Gift;
use app\services\reward\GiftRewardService;
use yii\base\Exception;

/**
 * Class GiftPrize
 * @package app\services\prize
 */
class GiftPrize extends AbstractPrize
{
    /** @var int */
    protected const LIMIT = 3;

    /** @var int */
    protected $currency = self::CURRENCY_GIFT;

    /**
     * @return string|GiftPrizeForm
     */
    public function getForm(): string
    {
        return GiftPrizeForm::class;
    }

    /**
     * @return string|GiftRewardService
     */
    protected function getRewardService(): string
    {
        return GiftRewardService::class;
    }

    /**
     * @return array
     */
    protected function getConfig(): array
    {
        return [
            self::LIMIT_ATTRIBUTE_NAME => self::LIMIT,
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getData(): array
    {
        if ($this->data === null) {
            $objectModel = $this->getRandGift();

            if ($objectModel === null) {
                throw new Exception('Undefined error. Please, try again later.');
            }

            $this->data = [
                'object' => [
                    'id' => $objectModel->id,
                ],
                'currency' => $this->currency,
            ];
        }

        return $this->data;
    }

    /**
     * @return Gift|null
     */
    protected function getRandGift(): ?Gift
    {
        return Gift::find()
            ->where(['>=', 'id', sprintf('FLOOR(RAND()*(SELECT MAX(id) FROM %s)', Gift::tableName())])
            ->limit(1)
            ->one();
    }
}
