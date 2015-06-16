<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Token;

/**
 * LoginForm is the model behind the login form.
 */
class ResetPasswordForm extends Model {

    public $password;
    public $password_repeat;
    /**
     * @return array the validation rules.
     */

    public function rules() {

        return [
            // username and password are both required
            //[['email', 'captcha'], 'required'],
            [['password', 'password_repeat'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
            [['password', 'password_repeat'], 'string' ,'min'=>6],
                // rememberMe must be a boolean value
        ];
    }

    public function getUser($email) {

        if ($this->user === false) {
            return $this->user = \app\models\User::find(['email', $email])->one();
        } else {
            return $this->user;
        }
    }

    public function sendValidationUrl($email) {
        if ($this->validate()) {
            /** @var Token $token */
            $token = \Yii::createObject([
                        'class' => Token::className(),
                        'user_id' => $this->getUser($email)->user_id,
                        'token_type' => Token::TYPE_RECOVERY,
            ]);
            
            $token->save(false);
            $this->mailer->sendRecoveryMessage($this->user, $token);
            \Yii::$app->session->setFlash('info', \Yii::t('app', 'An email has been sent with instructions for resetting your password'));
            return true;
        }
        return false;
    }

}
