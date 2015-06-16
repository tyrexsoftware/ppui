<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applications".
 *
 * @property integer $applications_id
 * @property string $appname
 * @property string $appkey
 * @property integer $status
 */
class Applications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          //  [['appname', 'appkey', 'status'], 'required'],
            [['status'], 'integer'],
            [['appname', 'appkey'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applications_id' => 'Applications ID',
            'appname' => 'Appname',
            'appkey' => 'Appkey',
            'status' => 'Status',
        ];
    }
}
