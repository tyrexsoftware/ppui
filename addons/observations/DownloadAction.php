<?php

namespace app\addons\observations;

use yii;
use yii\base\Action;
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

class DownloadAction extends Action {

    public $modelClass;

    public function run() {

        $params = [];
        $filters = Yii::$app->request->get('filters');

        foreach (preg_split('/&/', urldecode($filters)) as $value) {
            $pair = preg_split('/=/', $value);
            preg_match_all("/\[([^\]]*)\]/", $pair[0], $matches);
            $params[$matches[1][0]] = $pair[1];
        }

        $searchQ = new SearchObservation();
        $searchParams['SearchObservation'] = $params;

        $downloadClass = '\\app\\addons\\observations\\downloads\\' . ucfirst(Yii::$app->request->get('module'));

        if (class_exists($downloadClass)) {
            $dataProvider = $searchQ->search($searchParams, Yii::$app->request->get('module'), [Yii::$app->request->get('module').'Records']);
            $downloadClass = new $downloadClass;
            $downloadClass->generateDownload($dataProvider, $params);
        }
    }

}
