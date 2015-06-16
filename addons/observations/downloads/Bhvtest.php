<?php

namespace app\addons\observations\downloads;

use yii;
use app\models\BehaviourActions;
use app\models\Observations;
use app\models\User;
use app\addons\helpers\SearchObservation;
use app\addons\helpers\GeneralHelper;
use yii\db\Query;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Bhvtest {

    public $modelClass;

    public function generateDownload($dataProvider, $params) {

        $csv = [];
        if (false !== Yii::$app->request->get('type')) {
            switch (Yii::$app->request->get('type')) {
                case 'generalized' :

                    $filename = 'generalized_observations' . date("Ymd") . '.csv';
                    $csv[] = ['Observer', 'Date of Observation', 'Start of Observation',
                        'Location', 'Animal ID'];


                    /* Get */
                    $containerQuery = new Query;
                    $containerQuery->select(BehaviourActions::tableName() . '.container as container')->distinct();
                    $containerQuery->from('observations');
                    $containerQuery->leftJoin(BehaviourActions::tableName(), BehaviourActions::tableName() . '.observation_id' . '=' .
                            Observations::tableName() . '.observation_id');

                    $containerQuery->andFilterWhere([Observations::tableName() . '.' . 'appkey' => 'bhvtest']);

                    $observationsModel = new Observations();

                    $containerQuery = GeneralHelper::observationSearchHelper($observationsModel, $containerQuery, $params);


                    $totalContainers = $containerQuery->count();
                    $containersArray = [];

                    foreach ($containerQuery->all() as $containters) {
                        $containersArray[] = $containters['container'];
                        $csv[0][] = $containters['container'] . ' % (time)';
                    }


                    $containersArray = array_flip($containersArray);

                    $i = 1;
                    foreach ($dataProvider->query->all() as $individual_observation) {


                        $user = User::findOne(['user_id' => $individual_observation->observer_id]);
                        $user->first_name . ' ' . $user->last_name;
                        $csv[$i] = array_fill(0, 5 + $totalContainers, 0);
                        $csv[$i][0] = $user->first_name . ' ' . $user->last_name;

                        $csv[$i][1] = Yii::$app->formatter->asDate($individual_observation->observation_date);
                        $csv[$i][2] = Yii::$app->formatter->asTime($individual_observation->timestart);
                        $csv[$i][3] = $individual_observation->location;
                        $csv[$i][4] = $individual_observation->animal_id;

                        $behaviors = BehaviourActions::find()->where(['=', 'observation_id', $individual_observation->observation_id])->all();

                        $analyticRaw = [];
                        $analytic = [];


                        foreach ($behaviors as $action) {
                            if (!isset($analyticRaw[$action->container])) {
                                $analyticRaw[$action->container] = 0;
                            }
                            $analyticRaw[$action->container] = $analyticRaw[$action->container] + $action->duration;
                        }

                        foreach ($analyticRaw as $key => $individual_time) {
                            $csv[$i][$containersArray[$key] + 5] = round(100 * $individual_time / $individual_observation->total_observation_time, 2);
                        }

                        $i++;
                    }
                    break;

                case "individual" :
                    $filename = 'individual_behaviors' . date("Ymd") . '.csv';
                    $csv[] = array('Unique Observation ID', 'Observer', 'Date of Observation',
                        'Start of Observation', 'Location', 'Animal ID',
                        'Type of Behavior', 'Behavior', 'Recepient',
                        'Duration(seconds)', 'Comments');

                    $i = 1;

                    foreach ($dataProvider->query->all() as $individual_observation) {

                        $user = User::findOne(['user_id' => $individual_observation->observer_id]);
                        $user->first_name . ' ' . $user->last_name;

                        $individualActions = BehaviourActions::find()->where(['=', 'observation_id', $individual_observation->observation_id])->all();



                        foreach ($individualActions as $action) {

                            $csv[$i][] = $individual_observation->observation_id;
                            $csv[$i][] = $user->first_name . ' ' . $user->last_name;
                            $csv[$i][] = Yii::$app->formatter->asDate($individual_observation->observation_date);
                            $csv[$i][] = Yii::$app->formatter->asTime($individual_observation->timestart);
                            $csv[$i][] = $individual_observation->location;
                            $csv[$i][] = $individual_observation->animal_id;
                            $csv[$i][] = $action->container;
                            $csv[$i][] = $action->action;
                            $csv[$i][] = $action->animal_id_connection;
                            $csv[$i][] = $action->duration;
                            $csv[$i][] = $action->comment;

                            $i++;
                        }
                    }

                    break;
            }
        }

        $f = fopen('php://memory', 'w');
        $delimiter = ',';

        foreach ($csv as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        fseek($f, 0);
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachement; filename="' . $filename . '";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }

}
