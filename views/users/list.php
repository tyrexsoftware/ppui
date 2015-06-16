<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use kartik\grid\GridView;;
?>
<h1>organization/index</h1>


<?php \yii\widgets\Pjax::begin(); ?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'first_name',
        'last_name',
        'email',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'urlCreator' => function ( $action, $model, $key, $index ) {
        if ($action == "update") {
            return Url::to(['users/edit', 'user_id' => $model->user_id]);
        } elseif ($action == "delete") {
            return Url::to(['users/delete', 'user_id' => $model->user_id]);
        }
    },
        ],
    ]]
);
\yii\widgets\Pjax::end();
?>