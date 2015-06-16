<?php

use kartik\grid\GridView;
use kartik\grid\DataColumn;

?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'columns' => [
        'type',
        [
            'attribute' => 'time',
            'value' => function ($model, $key, $index, $column) {
        return gmdate("i:s", $model['time']);
    },
        ],
        'comment',
    ],
])
?>