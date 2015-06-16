<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applications2organization".
 *
 * @property integer $applications_id
 * @property integer $organization_id
 */
class Applications2organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applications2organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applications_id', 'organization_id'], 'safe'],
            //[['applications_id', 'organization_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applications_id' => 'Applications ID',
            'organization_id' => 'Organization ID',
        ];
    }
}
