<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contracts".
 *
 * @property integer $contract_id
 * @property integer $startdate
 * @property integer $enddate
 * @property string $comments
 */
class Contracts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contracts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startdate', 'enddate',], 'required'],
            [['startdate', 'enddate'], 'date'],
            [['organization_id'], 'integer'],
            [['comments'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contractid' => Yii::t('app', 'Contract ID'),
            'startdate' => Yii::t('app', 'Start Date'),
            'enddate' => Yii::t('app', 'End Date'),
            'organization_id' => Yii::t('app', 'Organization Id'),
            'comments' => Yii::t('app', 'Comments'),
        ];
    }
    public function beforeSave($insert) {
        echo '<pre>';
        var_dump($this->startdate);
        echo '</pre>';
die();
        parent::beforeSave($insert);
    }
}
