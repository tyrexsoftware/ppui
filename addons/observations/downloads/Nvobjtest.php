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

class Nvobjtest {

    public $modelClass;

    public function generateDownload($dataProvider, $params) {

        $csv = [];
        if (false !== Yii::$app->request->get('type')) {
            switch (Yii::$app->request->get('type')) {

                case "nvobjtest" :
                    $filename = 'Novel_Object_Output' . date("Ymd") . '.csv';
                    $csv[] = array(
                        'Unique Observation ID', 
                        'Observer', 
                        'Animal ID', 'Location',
                        'Date of Assessment', 'Time',
                        'Behavior Observation Completed', 
                        'Date of Behavior Observation', 
                        'Novel Object Test Type','Novel Object Test Item',
                        'Latency to Approach', 'Latency to Approach comment', 
                        'Latency to Touch', 'Latency to Touch comment', 
                        'Latency to Manipulate/Consume','Latency to Manipulate/Consume comment',
                        'Novel Object Score'
                        );

                    $i = 1;

                    
                    foreach ($dataProvider->query->all() as $individual_observation) {

                            $user = User::findOne(['user_id' => $individual_observation->observer_id]);
                            
                            $csv[$i][] = $individual_observation->observation_id;
                            $csv[$i][] = $user->first_name . ' ' . $user->last_name;
                            $csv[$i][] = $individual_observation->animal_id;
                            $csv[$i][] = $individual_observation->location;
                            $csv[$i][] = Yii::$app->formatter->asDate($individual_observation->observation_date);
                            $csv[$i][] = Yii::$app->formatter->asTime($individual_observation->observation_date);
                            $csv[$i][] = $individual_observation->nvobjtestRecords->bhvtest_completed==1?'Yes':'No';
                            $csv[$i][] = $individual_observation->nvobjtestRecords
                                    ->bhvtest_completed!==1?'':Yii::$app->formatter->asDate($individual_observation->nvobjtestRecords->testdate);
                            $csv[$i][] = $individual_observation->nvobjtestRecords->item_type;
                            $csv[$i][] = $individual_observation->nvobjtestRecords->novel_item;
                            $csv[$i][] = gmdate("i:s", $individual_observation->nvobjtestRecords->approach_time);
                            $csv[$i][] = $individual_observation->nvobjtestRecords->approach_comment;
                            $csv[$i][] = gmdate("i:s", $individual_observation->nvobjtestRecords->touch_time);
                            $csv[$i][] = $individual_observation->nvobjtestRecords->touch_comment;
                            $csv[$i][] = gmdate("i:s", $individual_observation->nvobjtestRecords->manipulate_time);
                            $csv[$i][] = $individual_observation->nvobjtestRecords->manipulate_comment;
                            $csv[$i][] = GeneralHelper::getNovelTestScore($individual_observation->nvobjtestRecords->approach_time);
                            
                            $i++;
                        
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
