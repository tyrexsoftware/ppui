<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "animals_sync".
 *
 * @property integer $record_id
 * @property integer $user_id
 * @property integer $animal_id
 * @property string $appkey
 */
class AnimalsSync extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'animals_sync';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['user_id', 'animal_id', 'appkey'], 'required'],
            [['user_id', 'animal_id'], 'integer'],
            [['appkey'], 'string', 'max' => 64]
        ];
    }

    public function fields() {

        $fields = parent::fields();
        unset($fields['record_id']);
        return $fields;
    }

    public function getAnimalsdescription() {
        return $this->hasOne(Animals::className(), ['animal_id' => 'animal_id']);
    }

    public function extraFields() {
        
        return [Yii::$app->request->get('expand')];
    }

    public function beforeDelete()
{
    if (parent::beforeDelete()) {
        
        $this->animalsdescription->delete();
        
        return true;
    } else {
        return false;
    }
}
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'record_id' => Yii::t('app', 'Record ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'animal_id' => Yii::t('app', 'Animal ID'),
            'appkey' => Yii::t('app', 'Appkey'),
        ];
    }

}
