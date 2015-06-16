<?php

namespace app\addons\observations;

use yii;
use yii\base\Action;
use app\models\NovelobjectActions;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\addons\helpers\SearchObservation;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NvobjtestAction extends Action {

    public $modelClass;

    public function run() {


        /*
         * Send Subgrid
         */
        if (Yii::$app->request->get('type') == 'subgrid') {

            $actionsQ = NovelobjectActions::find()
                            ->andWhere(['observation_id' => Yii::$app->request->post('expandRowKey')])->one();

            $dataArray = [
                ['type' => 'approach', 'time' => $actionsQ->approach_time, 'comment' => $actionsQ->approach_comment],
                ['type' => 'touch', 'time' => $actionsQ->touch_time, 'comment' => $actionsQ->touch_comment],
                ['type' => 'manipulate', 'time' => $actionsQ->manipulate_time, 'comment' => $actionsQ->manipulate_comment],
            ];

            $dataProvider = new ArrayDataProvider([
                'pagination' => false,
                'allModels' => $dataArray,]);
            return $this->controller->renderPartial('partials/' . $this->id, ['dataProvider' => $dataProvider]);
        }

        /*
         * Send Main Grid
         */

        $searchQ = new SearchObservation();

        $dataProvider = $searchQ->search(Yii::$app->request->get(), $this->id, ['nvobjtestRecords']);
        
        
        

        return $this->controller->render($this->id, [
                    'dataProvider' => $dataProvider,
                    'search' => $searchQ
        ]);
    }

}
