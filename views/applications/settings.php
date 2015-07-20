<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Manage Application Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['action' => ['applications/savesettings'], 'options' => ['enctype' => 'multipart/form-data']]); ?>


    <h3>Focal Behavior Observation Settings</h3>

<?= $form->field($model, 'behavorial_observation_time') ?>
    <hr>
    <h3>Hair Loss Assessment Settings</h3>


<?php

for ($i = 0; $i <= 3; $i++) : ?>
        <div class = "settingsline">
        <?= $form->field($model, 'alopecia_status_'.$i)->checkbox() ?>

            <?=
            $form->field($model, 'alopecia_color_' . $i)->widget(ColorInput::classname(), [
                'useNative' => false,
                'size' => 'sm',
                'options' => [
                    'placeholder' => 'Select color ...',
                    'readonly' => 'true'
                ],
                'pluginOptions' => [
                    'preferredFormat' => "rgb",
                ],
            ]);
            ?>
            <?= $form->field($model, 'alopecia_option_'.$i, ['options' => ['class' => 'setting_name']]) ?>

        </div>
<?php endfor; ?>


    <hr>
    <h3>Novel Object Temperament Test Settings</h3>

<?= $form->field($model, 'novel_object_observation_time') ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
<?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

</div>
