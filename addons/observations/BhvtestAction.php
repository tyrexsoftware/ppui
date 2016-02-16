<?php

namespace app\addons\observations;

use yii;
use yii\base\Action;
use app\models\Observations;
use app\models\BehaviourActions;
use yii\data\ActiveDataProvider;
use app\addons\helpers\SearchObservation;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BhvtestAction extends Action {

    public $modelClass;

    public function run() {

        
        /*
         * Send Subgrid
         */
        if (Yii::$app->request->get('type') == 'subgrid') {

            $actionsQ = BehaviourActions::find()
                    ->andWhere(['observation_id' => Yii::$app->request->post('expandRowKey')]);
            //$actionsQ->addOrderBy(['observation_date'=>'SORT_ASC']);


            $dataProvider = new ActiveDataProvider([
            'pagination' => false,

            'query' => $actionsQ,]);
            return $this->controller->renderPartial('partials/'.$this->id, ['dataProvider' => $dataProvider]);
        }
        
        /*
         * Send Main Grid
         */
        
        $searchQ = new SearchObservation();

        
        $dataProvider = $searchQ->search(Yii::$app->request->get(), $this->id);

        return $this->controller->render($this->id, [
                    'dataProvider' => $dataProvider,
                    'search' =>$searchQ
        ]);
    }

}
