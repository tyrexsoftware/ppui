<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use app\models\User;
use yii\bootstrap\Tabs;

$this->title = 'Manage Animals';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3>General Animals List </h3>

    <?=
    GridView::widget(
            [
                'dataProvider' => $dataprovider,
                'export' => false,
                'columns' => [
                    [
                        'class' => 'kartik\grid\CheckboxColumn',
                    ],
                    'name',
                    'location',
                    [
                        'class' => DataColumn::className(),
                        'content' => function($model, $key, $index, $column) {
                    $user = User::findOne(['user_id' => $model->user_id]);
                    return $user->first_name . ' ' . $user->last_name;
                },
                        'label' => 'Owner Name',
                    ],
                    [
                        'class' => DataColumn::className(),
                        'content' => function($model, $key, $index, $column) {
                    $names = '';
                    foreach ($model->syncedAnimals as $syncAnimal) {
                        $user = User::findOne(['user_id' => $syncAnimal->user_id]);
                        $names .= $user->first_name . ' ' . $user->last_name . '</br>';
                    }
                    return $names;
                },
                        'label' => 'Assigned Staff',
                    ],
                    [
                        'class' => DataColumn::className(),
                        'content' => function($model, $key, $index, $column) {
                    $appnames = '';
                    foreach ($model->syncedAnimals as $syncAnimal) {
                        $apps = preg_split('/,/', $syncAnimal->appkey);

                        foreach ($apps as $anApp) {
                            if ($anApp === 'all') {
                                $applicationName = 'All' . '</br>';
                            } else {
                                $appQuery = \app\models\Applications::findOne(['appkey' => $anApp]);
                                $applicationName = $appQuery->appname . '</br>';
                            }

                            $appnames .= $applicationName;
                        }
                    }
                    return $appnames;
                },
                        'label' => 'Applications',
                    ],
                ],
                'pjax' => true,
                'pjaxSettings' => [
                    'neverTimeout' => true,
                ]
            ]
    );
    ?>


</div>

<div id="massactiontabs" style="display: none;">
    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Mass Action',
                'content' => $this->render('partials/massaction'),
                'active' => true
            ]
        ]
    ]);
    ?>
</div>