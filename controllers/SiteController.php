<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\UserPrize;
use app\services\PrizeService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'login' => ['get', 'post'],
                    'index' => ['get', 'post'],
                    'refuse' => ['get'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
                return $this->redirect(['site/login']);
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
            return $this->redirect(['site/login']);
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
            return $this->redirect(['site/login']);
        }

        /** @var PrizeService $service */
        $service = \Yii::$container->get(PrizeService::class);
        $result = $service->refuse($model);

        if ($result === true) {
            \Yii::$app->session->addFlash('success', 'You have successfully declined a prize');
        } else {
            \Yii::$app->session->addFlash('error', 'Error refuse. Please, try again.');
        }

        return $this->redirect(['site/index']);
    }

    /**
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            \Yii::$app->session->addFlash('success', 'Account successfully register, Please, log in your account');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
