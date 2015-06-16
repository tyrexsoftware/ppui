<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_settings".
 *
 * @property integer $setting_id
 * @property string $appkey
 * @property integer $organization_id
 * @property string $setting_name
 * @property string $setting_value
 */
class ApplicationSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appkey', 'organization_id', 'setting_name', 'setting_value'], 'required'],
            [['organization_id'], 'integer'],
            [['appkey'], 'string', 'max' => 32],
            [['setting_name', 'setting_value'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => Yii::t('app', 'Setting ID'),
            'appkey' => Yii::t('app', 'Appkey'),
            'organization_id' => Yii::t('app', 'Organization ID'),
            'setting_name' => Yii::t('app', 'Setting Name'),
            'setting_value' => Yii::t('app', 'Setting Value'),
        ];
    }
}
