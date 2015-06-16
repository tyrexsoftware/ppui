<?php

namespace app\addons\observations;

use yii;
use yii\base\Action;
use app\models\AlopeciaActions;
use yii\data\ActiveDataProvider;
use app\addons\helpers\SearchObservation;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AlptestAction extends Action {

    public $modelClass;

    public function run() {

        
        /*
         * Send Subgrid
         */
        if (Yii::$app->request->get('type') == 'subgrid') {

            $actionsQ = AlopeciaActions::find()
                    ->andWhere(['observation_id' => Yii::$app->request->post('expandRowKey')]);

            $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $actionsQ,]);
            return $this->controller->renderPartial('partials/'.$this->id, ['dataProvider' => $dataProvider]);
        }
        
        /*
         * Send Main Grid
         */

        
        $searchQ = new SearchObservation();
        
        $searchDataProvider = new ActiveDataProvider(['query'=>$searchQ]);
        $dataProvider = $searchQ->search(Yii::$app->request->get(), $this->id);


        return $this->controller->render($this->id, [
                    'dataProvider' => $dataProvider,
                    'search' =>$searchQ
        ]);
    }

}
