<?php

namespace app\models\forms\prize;

use yii\helpers\ArrayHelper;

/**
 * Class MoneyPrizeForm
 * @package app\models\forms\prize
 */
class MoneyPrizeForm extends AbstractPrizeForm
{
    /**
     * @return float
     */
    public function getPrize(): float
    {
        return (float)ArrayHelper::getValue(json_decode($this->data, true), 'amount');
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
     * @throws \yii\base\InvalidConfigException
     */
    public function getFormattedText(): string
    {
        return \Yii::$app->formatter->asCurrency($this->getPrize());
    }
}
