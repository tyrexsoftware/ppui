<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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

        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
        

        <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    </div>
<?php endif; ?>
