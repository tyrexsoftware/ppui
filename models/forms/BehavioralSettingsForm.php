<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class BehavioralSettingsForm extends Model {

    public $behavorial_observation_time;
    public $novel_object_observation_time;
    public $xml_behavors;
    public $alopecia_option_0;
    public $alopecia_option_1;
    public $alopecia_option_2;
    public $alopecia_option_3;
    public $alopecia_color_0;
    public $alopecia_color_1;
    public $alopecia_color_2;
    public $alopecia_color_3;
    public $alopecia_status_0;
    public $alopecia_status_1;
    public $alopecia_status_2;
    public $alopecia_status_3;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['alopecia_color_1', 'alopecia_color_2', 'alopecia_color_3', 'alopecia_color_0'], 'string', 'max' => 32],
            [['alopecia_option_1', 'alopecia_option_2', 'alopecia_option_3', 'alopecia_option_0'], 'string', 'max' => 32],
            [['alopecia_status_1', 'alopecia_status_2', 'alopecia_status_3', 'alopecia_status_0'], 'integer', 'max' => 32],
            [['behavorial_observation_time', 'novel_object_observation_time'], 'required'],
            [['behavorial_observation_time', 'novel_object_observation_time'], 'integer'],
          //  ['xml_behavors', 'file', 'enableClientValidation'=>false, 'extensions'=>['xml', 'csv']],
            ['xml_behavors','file', 'when'=>'bububu']
        ];
    }
    
    public function bububu($attribute, $param) {
        
        $this->addError($attribute, 'eroarea');
        die();
    }

    public function attributeLabels() {
        return [
            'xml_behavors' => 'XML Ethogram',
        ];
    }

}
