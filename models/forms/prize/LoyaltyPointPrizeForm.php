<?php

namespace app\models\forms\prize;

use yii\helpers\ArrayHelper;

/**
 * Class LoyaltyPointPrizeForm
 * @package app\models\forms\prize
 */
class LoyaltyPointPrizeForm extends AbstractPrizeForm
{
    /**
     * @return int
     */
    public function getPrize(): int
    {
        return (int)ArrayHelper::getValue(json_decode($this->data, true), 'amount');
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'amount' => $this->getPrize(),
            'currency' => $this->getCurrency(),
        ];
    }

    /**
     * @return string
     */
    public function getFormattedText(): string
    {
        return sprintf('%d bonuses', \Yii::$app->formatter->asInteger($this->getPrize()));
    }
}
