<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\addons\Mailer;
use app\models\Token;

/**
 * LoginForm is the model behind the login form.
 */
class ForgotPasswordForm extends Model {

    public $email;
    protected $mailer;
    public $captcha;
    protected $user = false;

    /**
     * @return array the validation rules.
     */
    public function init() {
        $this->mailer = new Mailer();
    }

    public function rules() {

        return [
            // username and password are both required
            [['email', 'captcha'], 'required'],
            ['captcha', 'captcha'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => \app\models\User::className(),
                'message' => \Yii::t('app', 'There is no user with this email address')
            ],
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

}
