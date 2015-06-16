<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\forms\ForgotPasswordForm;
use app\models\ContactForm;

class SiteController extends \app\addons\Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['login', 'forgotpassword', 'resetpassword', 'captcha'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        
        
        return $this->render('index');
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionForgotpassword() {
        $model = new ForgotPasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                $model->sendValidationUrl($model->email);
            }
        }
        return $this->render('forgotpassword', [
                    'model' => $model,
        ]);
    }


    public function actionAbout() {
        return $this->render('about');
    }

}
