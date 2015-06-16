<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class CsvuploadForm extends Model {

    public $animalscsv;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['animalscsv', 'file'],
            ['user_id', 'integer'],
        ];
    }

}
