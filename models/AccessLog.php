<?php

namespace app\models;


use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "access_log".
 *
 * @property integer $access_id
 * @property string $access_type
 * @property string $udid
 * @property integer $acccessdate

 */
class AccessLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'access_log';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'acccessdate',
                'updatedAtAttribute' => 'acccessdate',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['acccessdate'],
                    self::EVENT_BEFORE_UPDATE => 'acccessdate',
                ],

            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['access_type', 'udid', 'organization_id', 'user_id'], 'required'],
            [['access_type', 'udid'], 'required'],
            [['acccessdate', 'organization_id', 'user_id'], 'integer'],
            [['access_type', 'udid'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'access_id' => Yii::t('app', 'Access ID'),
            'access_type' => Yii::t('app', 'Access Type'),
            'udid' => Yii::t('app', 'Udid'),
            'acccessdate' => Yii::t('app', 'Created At'),

        ];
    }

}
