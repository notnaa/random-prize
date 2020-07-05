<?php

namespace app\commands;

use app\models\UserPrize;
use app\services\PrizeService;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class PrizeController
 * @package app\commands
 */
class PrizeController extends Controller
{
    /** @var int */
    private const BATCH_COUNT = 100;

    /**
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionReward(): int
    {
        $successfullyRewarded = [];
        $successfullyWaitDeliveryRewarded = [];
        $errorRewarded = [];
        $offset = 0;
        /** @var PrizeService $service */
        $service = \Yii::$container->get(PrizeService::class);
        $query = UserPrize::find()
            ->where(['status' => UserPrize::STATUS_WAIT])
            ->limit(self::BATCH_COUNT);

        while ($models = $query->offset($offset)->all()) {
            $offset += self::BATCH_COUNT;

            if ($models === []) {
                break;
            }

            foreach ($models as $model) {
                try {
                    if (!$service->reward($model)) {
                        throw new Exception('Undefined error on trying reward.');
                    }

                    if ($model->status === $model::STATUS_WAIT_DELIVERY) {
                        $successfullyWaitDeliveryRewarded[] = $model->id;
                    } else {
                        $successfullyRewarded[] = $model->id;
                    }
                } catch (\Exception $e) {
                    $errorRewarded[] = $model->id;
                    $this->stderr(sprintf('Error reward upid:%d with uid:%d. Error message: %s%s',
                        $model->id,
                        $model->user_id,
                        $e->getMessage(),
                        PHP_EOL
                    ), Console::FG_RED);
                }
            }
        }

        if ($successfullyRewarded !== []) {
            UserPrize::updateAll(['status' => UserPrize::STATUS_RECEIVED], ['id' => $successfullyRewarded]);
        }

        if ($successfullyWaitDeliveryRewarded !== []) {
            UserPrize::updateAll(['status' => UserPrize::STATUS_WAIT_DELIVERY], ['id' => $successfullyWaitDeliveryRewarded]);
        }

        if ($errorRewarded !== []) {
            UserPrize::updateAll(['status' => UserPrize::STATUS_ERROR], ['id' => $errorRewarded]);
        }

        $this->stdout(sprintf('Successfully reward %d of prices.%s',
            count($successfullyRewarded) + count($successfullyWaitDeliveryRewarded),
            PHP_EOL
        ), Console::FG_GREEN);

        return ExitCode::OK;
    }
}
