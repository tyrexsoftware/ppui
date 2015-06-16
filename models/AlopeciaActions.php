<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alopecia_actions".
 *
 * @property integer $action_id
 * @property integer $observation_id
 * @property integer $bodypart
 * @property string $alopecia_type
 * @property string $color
 * @property double $percentage
 * @property string $comment
 */
class AlopeciaActions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alopecia_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['observation_id', 'bodypart', 'alopecia_type', 'color', 'percentage'], 'required'],
            [['observation_id', 'bodypart'], 'integer'],
            [['percentage'], 'number'],
            [['comment'], 'string'],
            [['alopecia_type', 'color'], 'string', 'max' => 32]
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
            'bodypart' => Yii::t('app', 'Bodypart'),
            'alopecia_type' => Yii::t('app', 'Alopecia Type'),
            'color' => Yii::t('app', 'Color'),
            'percentage' => Yii::t('app', 'Percentage'),
            'comment' => Yii::t('app', 'Comment'),
        ];
    }
}
