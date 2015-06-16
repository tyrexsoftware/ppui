<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ForgotPasswordForm */
/* @var $form ActiveForm */
$this->title = 'Password Recovery';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (\Yii::$app->session->hasFlash('info')): ?>

    <div class="alert alert-success">
        <?= \Yii::$app->session->getFlash('info') ?>
    </div>
<?= Html::a('Return to Login', Url::to(['site/login']))?>


<?php else: ?>
    <div class="site-forgotpassword">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email') ?>
        <?=
        $form->field($model, 'captcha')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ])
        ?>

        <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    </div>
<?php endif; ?>
