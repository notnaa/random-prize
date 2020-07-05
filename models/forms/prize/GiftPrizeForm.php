<?php

namespace app\models\forms\prize;

use app\models\Gift;
use yii\helpers\ArrayHelper;

/**
 * Class GiftPrizeForm
 * @package app\models\forms\prize
 */
class GiftPrizeForm extends AbstractPrizeForm
{
    /**
     * @return int
     */
    public function getPrize(): int
    {
        return (int)ArrayHelper::getValue(json_decode($this->data, true), 'object.id');
    }

    /**
     * @return int
     */
    public function getCurrency(): int
    {
        return (int)ArrayHelper::getValue(json_decode($this->data, true), 'currency');
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'object' => [
                'id' => $this->getPrize(),
            ],
            'currency' => $this->getCurrency(),
        ];
    }

    /**
     * @return string
     */
    public function getFormattedText(): string
    {
        $gift = Gift::findOne(['id' => $this->getPrize()]);
        return sprintf('Gift: %s', $gift->name);
    }
}
