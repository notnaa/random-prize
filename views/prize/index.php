<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var \app\models\forms\prize\PrizeFormInterface|null $result
 */

$this->title = 'Raffle prizes';
?>

<div class="site-index">
    <div class="jumbotron">
        <h1>Raffle prizes!</h1>

        <?php if (\Yii::$app->getUser()->isGuest) : ?>
            <p>
                <?= Html::a('Get prize', ['auth/login'], ['class' => 'btn btn-lg btn-success']); ?>
            </p>
        <?php else : ?>
            <?php Pjax::begin(); ?>
            <?php $form = ActiveForm::begin([
                'id' => 'prize-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]); ?>

            <div class="form-group">
                <div class="col-lg-11">
                    <?= Html::submitButton('Get prize', ['class' => 'btn btn-primary', 'name' => 'login-button']); ?>
                </div>

                <div class="col-lg-11">
                    <?php if ($result !== null) : ?>
                        <p>Your prize:</p>
                        <p>
                            <?= $result->getFormattedText(); ?>
                        </p>
                    <p>
                        <?= Html::a('Refuse', ['prize/refuse', 'id' => $result->getModel()->id]); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
        <?php endif; ?>
    </div>
</div>
