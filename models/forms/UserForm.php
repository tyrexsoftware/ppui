<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class UserForm extends Model {

    public $first_name;
    public $last_name;
    public $email;
    public $timezone;
    public $password;
    public $password_repeat;
    public $user_id;
    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['first_name', 'last_name', 'email', 'timezone'], 'required'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 64],
            ['password', 'compare', 'compareAttribute' => 'password_repeat', 'on' => array('create', 'edit')],
            [['password', 'password_repeat'], 'string' ,'min'=>6],
            [['password', 'password_repeat'], 'required' ,'on'=>'create'],
            ['user_id' ,'safe'],
            ['email' ,'email'],
        ];
    }

}
