<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\DataColumn;
use app\models\user;
use kartik\grid\ExpandRowColumn;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

$js = <<<JS

$("#searchobservation-observation_date").on('apply.daterangepicker', function(ev, picker) { $('.grid-view').yiiGridView('applyFilter');});
$("#w0-pjax").on('pjax:complete', function(){ 
$("#searchobservation-observation_date").on('apply.daterangepicker', function(ev, picker) { $('.grid-view').yiiGridView('applyFilter');});
$("#searchobservation-observation_date").daterangepicker(daterangepicker_00000000);
});
$("#nvobjtest").on('click', function(){
        filters = $("#w0-filters input").serialize();
   
        $("#downloadframe").attr('src',"download?filters="+encodeURIComponent(filters)+"&module=nvobjtest"+"&type="+event.target.id);
   })
        
JS;
$this->registerJs($js);


/* @var $this yii\web\View */
$this->title = 'Novel Object Temperament Test Results';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <ul>
        <li><a id="nvobjtest" href="javascript:;">Download Test Results</a></li>

    </ul>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'export' => false,
//        'pjaxSettings' => [
//            'options' => [
//                'enablePushState' => false,
//            ],
//        ],
        'filterModel' => $search,
        'columns' => [
            ['class' => ExpandRowColumn::className(),
                'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
                'detailUrl' => Url::to([
                    'observations/nvobjtest',
                    'type' => 'subgrid']),
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'nvobjtestRecords.testdate',
                'format' => 'date',
                'label' => 'Test Date'
            ],
            'animal_id',
            'location',
            [
                'class' => DataColumn::className(),
                'content' => function($model, $key, $index, $column) {
            $user = user::findOne(['user_id' => $model->observer_id]);
            return $user->first_name . ' ' . $user->last_name;
        },
                'label' => 'Observer Name',
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'observation_date',
                'format' => 'date',
                'filter' => DateRangePicker::widget(
                        [
                            'model' => $search,
                            'attribute' => 'observation_date',
                            'options' => [
                                'class' => 'form-control',
                                'readonly' => true,
                                'style' => 'cursor: pointer'
                            ],
                        ]
                )
            ],
            [
                'class' => DataColumn::className(),
                'value' => function ($model, $key, $index, $column) {
            return gmdate("i:s", $model->total_observation_time);
        },
                'label' => 'Duration'
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'timestart',
                'label'=>"Time Start",
                'format' => 'time',
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'timeend',
                'label'=>"Time End",
                'format' => 'time',
            ],
            [
                'class' => DataColumn::className(),
                'value' => function ($model, $key, $index, $column) {

            return $model->nvobjtestRecords->bhvtest_completed == 0 ? 'No' : 'Yes';
        },
                'label' => 'Behaviorist Test Completed'
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'nvobjtestRecords.bhvtest_completion_date',
                'format' => 'date',
                'label' => 'Behaviorist Test Date'
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'nvobjtestRecords.item_type',
                'label' => 'Item Type'
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'nvobjtestRecords.novel_item',
                'label' => 'Novel Item'
            ],
        ],
    ])
    ?>

</div>

<iframe id="downloadframe" style="display:none"></iframe>
