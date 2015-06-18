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

                        $apps = preg_split('/,/', $syncAnimal->appkey);
                        $appnames = '';
                        foreach ($apps as $anApp) {
                            if ($anApp === 'all') {
                                $images = '<i class="bhvtest" alt=></i><i class="alptest"></i><i class="nvobjtest"></i>';
                            } else {
                                $appQuery = \app\models\Applications::findOne(['appkey' => $anApp]);
                                $images = '<i class="'.$anApp.'" title="'. $appQuery->appname.'"></i>';
                            }

                            $appnames .= $images;
                        }

                        $names .= $user->first_name . ' ' . $user->last_name . $appnames. '</br>';
                    }
                    return $names;
                },
                        'label' => 'Assigned Staff',
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