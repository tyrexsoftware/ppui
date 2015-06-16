<?php

namespace app\controllers;

use yii;

class ObservationsController extends \app\addons\Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    public function actions() {

        $requestURL = Yii::$app->urlManager->parseRequest(Yii::$app->request);
        $executedAction = str_replace('observations/', '', $requestURL[0]);

        $actions = [
            $executedAction => [
                'class' => 'app\addons\observations\\' . ucfirst($executedAction) . 'Action',
            ],
        ];
        return array_merge(parent::actions(), $actions);
    }
}
