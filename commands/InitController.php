<?php

namespace app\commands;

use app\models\Gift;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class InitController
 * @package app\commands
 */
class InitController extends Controller
{
    /** @var array */
    private const GIFTS_DATA = [
        [
            'name' => 'Gift #1',
        ],
        [
            'name' => 'Gift #2',
        ],
        [
            'name' => 'Gift #3',
        ],
        [
            'name' => 'Gift #4',
        ],
        [
            'name' => 'Gift #5',
        ],
    ];

    /**
     * @return int
     * @throws \yii\db\Exception
     */
    public function actionData(): int
    {
        \Yii::$app->db
            ->createCommand()
            ->batchInsert(Gift::tableName(), ['name'], self::GIFTS_DATA)
            ->execute();

        return ExitCode::OK;
    }
}
