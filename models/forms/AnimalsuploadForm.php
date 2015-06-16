<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AnimalsuploadForm extends Model {

    public $animalscsv;
    public $typeofupload;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['animalscsv', 'file'],
            ['typeofupload', 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'typeofupload' => Yii::t('app', 'Type of Upload'),
        ];
    }

}
