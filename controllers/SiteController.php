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

    public function actionXml() {
        $behaviorsXML = simplexml_load_file(Yii::getAlias('@app') . '/config/ethogram.xsd');
        $behaviorsGrid = array();
        $container_order = 0;
        foreach ($behaviorsXML as $box => $values) {
            $eithogramContainer = new \app\models\EthogramContainer();
            
            $containername = (string) $values['name'];
            $eithogramContainer->container_key = \app\addons\helpers\GeneralHelper::cleansting((string) $values['name']);
            $eithogramContainer->container_name =(string) $values['name'];
            $eithogramContainer->sort_order = $container_order;
            $eithogramContainer->user_id = Yii::$app->user->identity->user_id;
            if($eithogramContainer->save()){
                $container_id = $eithogramContainer->container_id;
            }
            $container_order++;
            unset($eithogramContainer);
            $elements_order = 0;     
            foreach ($values->value as $id => $conainers) {
                $eithogramElements = new \app\models\EthogramElements();
                $eithogramElements->container_id = $container_id;
                $eithogramElements->element_key = \app\addons\helpers\GeneralHelper::cleansting((string) $conainers['name']);
                $eithogramElements->element_name = (string) $conainers['name'];
                $eithogramElements->sort_order = $elements_order;
                $eithogramElements->recepient = $conainers['linkable'] && (bool) $conainers['linkable'] ? 1 : 0;
                
                echo '<pre>';
                var_dump($eithogramElements->save());
                var_dump($eithogramElements->getErrors());
                echo '</pre>';
                unset($eithogramContainer);
                $elements_order++;

                $behaviorsGrid[(string) $values['name']][] = [
                    'name' => (string) $conainers['name'],
                    'linkable' => null !== $conainers['linkable'] && (bool) $conainers['linkable'] ? true : false];
            }
        }
        die();
    }

}
