<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Class PrizeController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
