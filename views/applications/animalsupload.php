<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kolyunya\yii2\widgets\bootstrap\RadioList;

$this->title = 'Upload Animals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-upload">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3><?= $this->title ?> </h3>


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'layout' => 'horizontal']); ?>

    <?=$form->field($model, 'typeofupload')->widget(
    'kolyunya\yii2\widgets\bootstrap\RadioList',
    [
        'items' =>
        [
            'merge' => 'Merge',
            'join' => 'Join',
            'replace' => 'Replace'
        ],
        'type' => 'primary',
        'size' => 'default',

    ]
);?>
    



    <?php
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'animalscsv',
        //'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['/applications/uploadfile']),
            'maxFileCount' => 1,
            'allowedFileExtensions' => ['csv', 'txt'],
            'allowedFileTypes' => ['text'],
            'allowedPreviewTypes' => [''],
            'uploadExtraData' => new \yii\web\JsExpression('function(){
                var obj = {}; 
                obj[\'typeofupload\'] = $("input:radio[name=\'AnimalsuploadForm[typeofupload]\']:checked").val() 
                
                return obj;}'),
        ],
        'pluginEvents' => [
            "fileerror" => 'function(event, file, previewId, index, reader) {setTimeout(function(){$("#animalsuploadform-animalscsv").fileinput(\'clear\'); }, 2000);}',
            "fileuploaded" => 'function(event, file, previewId, index, reader) {window.location.href = "manageanimals"}',
                    ]
    ]);
    ?>

    <?php ActiveForm::end(); ?>

</div>
