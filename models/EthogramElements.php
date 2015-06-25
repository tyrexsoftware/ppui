<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ethogram_elements".
 *
 * @property integer $element_id
 * @property integer $container_id
 * @property integer $element_name
 * @property integer $element_key
 * @property integer $sort_order
 * @property integer $recepient
 */
class EthogramElements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ethogram_elements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['container_id', 'element_name', 'element_key', 'sort_order', 'recepient'], 'required'],
            [['element_name', 'element_key'], 'string', 'max' => 32],
            [['container_id', 'sort_order', 'recepient'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'element_id' => Yii::t('app', 'Element ID'),
            'container_id' => Yii::t('app', 'Container ID'),
            'element_name' => Yii::t('app', 'Element Name'),
            'element_key' => Yii::t('app', 'Element Key'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'recepient' => Yii::t('app', 'Recepient'),
        ];
    }
}
