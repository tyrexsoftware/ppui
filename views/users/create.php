<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin(['action' => ['users/save'],]); ?>
    <?php
    $timezones = \DateTimeZone::listIdentifiers();
    foreach ($timezones as $zone) {
        $listoftimezones[$zone] = $zone;
    }

    ?>

    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'last_name') ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'timezone')->dropDownList($listoftimezones) ?>
    <?= $form->field($model, 'password') ?>
    <?= $form->field($model, 'password_repeat') ?>
    <?= $form->field($model, 'user_id')->hiddenInput()->label(false); ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
