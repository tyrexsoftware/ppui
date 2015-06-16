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
        
$("#alopecia").on('click', function(event){
        $('#popup').modal('show');
        
    filters = $("#w0-filters input").serialize();
        $("#downloadframe").attr('src',"download?filters="+encodeURIComponent(filters)+"&module=alptest"+"&type="+event.target.id);
   })        
        
JS;
$this->registerJs($js);


/* @var $this yii\web\View */
$this->title = 'Hair Loss Assessment Test Results';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
     <ul>
        <li><a id="alopecia" href="javascript:;">Download Test Results</a></li>
     </ul>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'export'=>false,
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
                    'observations/alptest',
                    'type' => 'subgrid']),
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
                            ],
                        ]
                )
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'observation_date',
                'filter' => false,
                'label' => 'Observation Time',
                'format' => 'time',
            ],
                'total_score'

        ],
    ])
    ?>

</div>
<iframe id="downloadframe" style="display:none"></iframe>