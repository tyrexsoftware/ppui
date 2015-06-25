<?php

namespace app\addons\helpers;

use yii;
use yii\base\Object;
use app\models\Animals;
use app\models\AccessLog;
use app\models\User;

class HomePage extends Object {

    public function getTotalAnimals() {
        return Animals::find()->andFilterWhere(['=', 'organization_id', Yii::$app->user->identity->organization_id])->count();
    }
    public function getTotalUserAnimals() {
        return Animals::find()->andFilterWhere(['=', 'organization_id', Yii::$app->user->identity->organization_id])->
                andFilterWhere(['=','user_id', Yii::$app->user->identity->user_id])->count();
    }

    public function getTotalDevices() {
        return AccessLog::find()->andFilterWhere(['organization_id' => Yii::$app->user->identity->organization_id])->select(['udid'])->distinct()->count();
    }

    public function getTotalUsers() {
        return User::find()->andFilterWhere(['=', 'organization_id', Yii::$app->user->identity->organization_id])->count();
    }

    public function getExiperyDate() {
        return 'TBD';
    }

    public function getLastSyncDay() {
        $lastSyncDay = AccessLog::find();

        if (Yii::$app->user->identity->is_manager == 1) {
            $lastSyncDay->andFilterWhere(['=', 'organization_id', Yii::$app->user->identity->organization_id]);
        } else {
            $lastSyncDay->andFilterWhere(['=', 'user_id', Yii::$app->user->identity->user_id]);
        }

        $lastSyncDay->orderBy('acccessdate');
        if (isset($lastSyncDay->one()->acccessdate)) {
            return Yii::$app->formatter->asDatetime($lastSyncDay->one()->acccessdate);
        } else {
            return 'No Sync Done';
        }
    }

    public function getNumberOfSyncedAnimals() {
        return 'TBD';
    }

    public function getLatestAndroidVersion() {
        return 'TBD';
    }

    public function getLatestiOSVersion() {
        return 'TBD';
    }

}
