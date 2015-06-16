<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use app\models\NovelobjectActions;
use Yii;

/**
 * This is the model class for table "observations".
 *
 * @property integer $observation_id
 * @property integer $appkey
 * @property integer $observer_id
 * @property integer $organization_id
 * @property integer $sync_user_id
 * @property integer $local_id
 * @property string $animal_id
 * @property string $location
 * @property string $total_score
 * @property integer $observation_date
 * @property integer $total_observation_time
 * @property integer $timestart
 * @property integer $timeend
 * @property integer $number_of_actions
 * @property integer $created_at
 */
class Observations extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'observations';
    }
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'created_at',
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['observer_id', 'appkey', 'organization_id', 'sync_user_id', 'local_id', 'animal_id', 'location', 'observation_date', 'number_of_actions'], 'required'],
            [['observer_id', 'organization_id', 'sync_user_id', 'observation_date', 'total_observation_time', 'timestart', 'timeend', 'number_of_actions', 'created_at'], 'integer'],
            [['total_score'], 'required', 'on' => 'alptest'],
            [['total_observation_time', 'timestart', 'timeend'], 'required', 'on' => 'bhvtest'],
            [['total_observation_time', 'timestart', 'timeend'], 'required', 'on' => 'nvobjtest'],
            [['animal_id', 'location'], 'string', 'max' => 64],
            [['total_score'], 'number'],
            [['appkey', 'local_id'], 'string', 'max' => 32],
        ];
    }
    
    public function getNvobjtestRecords(){

        return $this->hasOne(NovelobjectActions::className(), ['observation_id'=>'observation_id']);
            
    }
    public function getAlptestRecords(){

        return $this->hasMany(AlopeciaActions::className(), ['observation_id'=>'observation_id']);
            
    }
    public function getBhvtestRecords(){

        return $this->hasMany(BehaviourActions::className(), ['observation_id'=>'observation_id']);
            
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'observation_id' => Yii::t('app', 'Observation ID'),
            'appkey' => Yii::t('app', 'Application Key'),
            'observer_id' => Yii::t('app', 'Observer ID'),
            'organization_id' => Yii::t('app', 'Organization ID'),
            'sync_user_id' => Yii::t('app', 'Sync User ID'),
            'local_id' => Yii::t('app', 'Local ID'),
            'animal_id' => Yii::t('app', 'Animal ID'),
            'location' => Yii::t('app', 'Location'),
            'total_score' => Yii::t('app', 'Total Score'),
            'observation_date' => Yii::t('app', 'Observation Date'),
            'total_observation_time' => Yii::t('app', 'Total Observation Time'),
            'timestart' => Yii::t('app', 'Timestart'),
            'timeend' => Yii::t('app', 'Timeend'),
            'number_of_actions' => Yii::t('app', 'Number Of Actions'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

}
