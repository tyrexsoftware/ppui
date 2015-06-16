<?php

namespace app\addons;

use yii;
use app\models\User;
use app\models\Applications2organization;
use app\models\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller extends yii\web\Controller {

    public $availableApps = [];

    public function init() {


        if (!Yii::$app->user->isGuest) {
            $Apps = Applications::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])
                    ->leftJoin(Applications2organization::tableName(), 'applications.applications_id = applications2organization.applications_id')
                    ->all();
            $this->availableApps[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/'], 'visible' => !Yii::$app->user->isGuest];
            $this->availableApps[] = ['label' => Yii::t('app', 'Manage App Settings'), 'url' => ['applications/settings'], 'visible' => !Yii::$app->user->isGuest];
            $this->availableApps[] = ['label' => Yii::t('app', 'Manage Animals'), 'visible' => !Yii::$app->user->isGuest, 'items' => [
                    ['label' => Yii::t('app', 'Upload Animals'), 'url' => ['applications/uploadanimals', 'visible' => !Yii::$app->user->isGuest]],
                    ['label' => Yii::t('app', 'Manage Animals'), 'url' => ['applications/manageanimals', 'visible' => !Yii::$app->user->isGuest]]
                ]
            ];
            foreach ($Apps as $item) {
                $this->availableApps[] = ['label' => Yii::t('app', $item->appname), 'items' => [
                        ['label' => Yii::t('app', 'Manage Recorded Data'), 'url' => ['observations/' . $item->appkey]],
                    ], 'visible' => !Yii::$app->user->isGuest];
            }
            
        \Yii::$app->setTimeZone(Yii::$app->user->identity->timezone);
        }

        parent::init();
    }

}
