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

class Alptest {

    public $modelClass;

    public function generateDownload($dataProvider, $params) {

        $csv = [];
        if (false !== Yii::$app->request->get('type')) {
            switch (Yii::$app->request->get('type')) {

                case "alopecia" :
                    $filename = 'Alopecia_Output' . date("Ymd") . '.csv';
                    $csv[] = array('Unique Observation ID', 'Observer', 'Date of Assessment',
                        'Location', 'Animal ID', 'Type of Alopecia', 'View',
                        'Location on Body', 'Specific Percent of Body Affected',
                        'Comments', 'Total Percent of Body Affected');

                    $i = 1;

                    foreach ($dataProvider->query->all() as $individual_observation) {

                        $user = User::findOne(['user_id' => $individual_observation->observer_id]);

                        foreach ($individual_observation->alptestRecords as $alopeciaItem) {

                            $csv[$i][] = $individual_observation->observation_id;
                            $csv[$i][] = $user->first_name . ' ' . $user->last_name;
                            $csv[$i][] = Yii::$app->formatter->asDate($individual_observation->observation_date);
                            $csv[$i][] = $individual_observation->location;
                            $csv[$i][] = $individual_observation->animal_id;
                            $csv[$i][] = $alopeciaItem->alopecia_type;
                            $csv[$i][] = GeneralHelper::getViewById($alopeciaItem->bodypart); //$alopeciaItem->alopecia_type;
                            $csv[$i][] = GeneralHelper::getBodyPartById($alopeciaItem->bodypart);
                            $csv[$i][] = $alopeciaItem->percentage;
                            $csv[$i][] = $alopeciaItem->comment;
                            $csv[$i][] = $individual_observation->total_score;
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
