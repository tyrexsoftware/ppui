<?php

use kartik\grid\GridView;
use kartik\grid\DataColumn;

?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export'=>false,
    'columns' => [
        [
            'class' => DataColumn::className(),
            'attribute' => 'start_time',
            'format' => 'time',
        ],
        [
            'class' => DataColumn::className(),
            'attribute' => 'end_time',
            'format' => 'time',
        ],
        [

            'attribute' => 'duration',
            'value' => function ($model, $key, $index, $column) {
        return gmdate("i:s", $model->duration);
    },
        ],
        'container',
        'action',
        [
            'class' => DataColumn::className(),
            'attribute' => 'animal_id_connection',
            'label' => 'Recepient'
        ],
        'comment'
    ],
])
?>