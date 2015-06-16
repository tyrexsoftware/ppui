<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\AnimalsSync;

/**
 * This is the model class for table "animals".
 *
 * @property integer $animal_id
 * @property integer $user_id
 * @property integer $organization_id
 * @property string $name
 * @property string $location
 */
class Animals extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'animals';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //  [['user_id', 'organization_id', 'name', 'location'], 'required'],
            [['user_id', 'organization_id'], 'integer'],
            [['name', 'location'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'animal_id' => Yii::t('app', 'Animal ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'organization_id' => Yii::t('app', 'Organization ID'),
            'name' => Yii::t('app', 'Name'),
            'location' => Yii::t('app', 'Location'),
        ];
    }

    public function getSyncedAnimals() {
    
        return $this->hasMany(AnimalsSync::className(), ['animal_id' => 'animal_id']);
    }

    public function saveAnimals(array $animalList, $appkey = 'all') {

        $usersDb = new User();
        $animalsSync = new AnimalsSync();

        $this->location = $animalList[1];
        $this->user_id = Yii::$app->user->identity->user_id;
        $this->organization_id = Yii::$app->user->identity->organization_id;
        $this->name = $animalList[0];
        $this->save();

        $animalsSync->animal_id = $this->animal_id;
        $animalsSync->user_id = Yii::$app->user->identity->user_id;
        $animalsSync->appkey = $appkey;
        $animalsSync->save();


        return $this->animal_id;
    }

}
