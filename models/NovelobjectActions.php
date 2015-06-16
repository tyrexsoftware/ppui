<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novelobject_actions".
 *
 * @property integer $action_id
 * @property integer $observation_id
 * @property integer $bhvtest_completed
 * @property integer $bhvtest_completion_date
 * @property integer $testdate
 * @property string $item_type
 * @property string $novel_item
 * @property integer $approach_time
 * @property string $approach_comment
 * @property integer $touch_time
 * @property string $touch_comment
 * @property integer $manipulate_time
 * @property string $manipulate_comment
 */
class NovelobjectActions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'novelobject_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['observation_id', 'testdate', 'item_type', 'novel_item'], 'required'],
            [['observation_id', 'bhvtest_completed', 'bhvtest_completion_date', 'testdate', 'approach_time', 'touch_time', 'manipulate_time'], 'integer'],
            [['item_type'], 'string'],
            [['novel_item', 'approach_comment', 'touch_comment', 'manipulate_comment'], 'string', 'max' => 64]
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
            'bhvtest_completed' => Yii::t('app', 'Bhvtest Completed'),
            'bhvtest_completion_date' => Yii::t('app', 'Bhvtest Completion Date'),
            'testdate' => Yii::t('app', 'Testdate'),
            'item_type' => Yii::t('app', 'Item Type'),
            'novel_item' => Yii::t('app', 'Novel Item'),
            'approach_time' => Yii::t('app', 'Approach Time'),
            'approach_comment' => Yii::t('app', 'Approach Comment'),
            'touch_time' => Yii::t('app', 'Touch Time'),
            'touch_comment' => Yii::t('app', 'Touch Comment'),
            'manipulate_time' => Yii::t('app', 'Manipulate Time'),
            'manipulate_comment' => Yii::t('app', 'Manipulate Comment'),
        ];
    }
}
