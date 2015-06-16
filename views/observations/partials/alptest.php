<?php

use kartik\grid\GridView;
use kartik\grid\DataColumn;
use app\addons\helpers\GeneralHelper;

?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export'=>false,
    'columns' => [
        [
            'class' => DataColumn::className(),
            'attribute' => 'bodypart',
            'value' => function($model, $key, $index, $column) {
        return GeneralHelper::getBodyPartById($model->bodypart);
    },
        ],
        'alopecia_type',
        'percentage',
        'comment'
    ],
])
?>