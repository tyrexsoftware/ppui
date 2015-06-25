<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ethogram_container".
 *
 * @property integer $container_id
 * @property string $container_key
 * @property string $container_name
 * @property integer $sort_order
 * @property integer $user_id
 */
class EthogramContainer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ethogram_container';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['container_key', 'container_name', 'sort_order', 'user_id'], 'required'],
            [['sort_order', 'user_id'], 'integer'],
            [['container_key', 'container_name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'container_id' => Yii::t('app', 'Container ID'),
            'container_key' => Yii::t('app', 'Container Key'),
            'container_name' => Yii::t('app', 'Container Name'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
}
