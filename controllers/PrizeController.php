<?php

namespace app\controllers;

use app\models\UserPrize;
use app\services\PrizeService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PrizeController
 * @package app\controllers
 */
class PrizeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get', 'post'],
                    'refuse' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionIndex(): string
    {
        $result = null;

        if (\Yii::$app->request->isPost) {
            if (Yii::$app->user->isGuest) {
                return $this->redirect(['auth/login']);
            }

            /** @var PrizeService $service */
            $service = \Yii::$container->get(PrizeService::class);
            $result = $service->playOut(\Yii::$app->getUser()->getIdentity());

            if ($result === null) {
                \Yii::$app->session->addFlash('error', 'Error getting prize. Please, try again!');
            }
        }

        return $this->render('index', ['result' => $result]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionRefuse(int $id): string
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['auth/login']);
        }

        $model = UserPrize::find()
            ->andWhere(['id' => $id])
            ->andWhere(['user_id' => (int)\Yii::$app->getUser()->getId()])
            ->andWhere(['status' => UserPrize::STATUS_WAIT])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['auth/login']);
        }

        /** @var PrizeService $service */
        $service = \Yii::$container->get(PrizeService::class);
        $result = $service->refuse($model);

        if ($result === true) {
            \Yii::$app->session->addFlash('success', 'You have successfully declined a prize');
        } else {
            \Yii::$app->session->addFlash('error', 'Error refuse. Please, try again.');
        }

        return $this->redirect(['prize/index']);
    }
}
