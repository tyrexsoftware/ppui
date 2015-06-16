<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "behaviour_actions".
 *
 * @property integer $action_id
 * @property integer $observation_id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $duration
 * @property string $container
 * @property string $action
 * @property string $animal_id_connection
 * @property string $comment
 */
class BehaviourActions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'behaviour_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['observation_id', 'start_time', 'end_time', 'duration', 'container', 'action'], 'required'],
            [['observation_id', 'start_time', 'end_time', 'duration'], 'integer'],
            [['comment'], 'string'],
            [['container', 'action'], 'string', 'max' => 64],
            [['animal_id_connection'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => Yii::t('app', 'Action ID'),
            'observation_id' => Yii::t('app', 'Observation ID'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'duration' => Yii::t('app', 'Duration'),
            'container' => Yii::t('app', 'Container'),
            'action' => Yii::t('app', 'Action'),
            'animal_id_connection' => Yii::t('app', 'Animal Id Connection'),
            'comment' => Yii::t('app', 'Comment'),
        ];
    }
}
