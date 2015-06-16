<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\UploadedFile;
use yii\base\Security;
use yii\data\ActiveDataProvider;
use app\models\forms\UserForm;
use yii\filters\VerbFilter;
use app\models\Token;
use app\models\forms\ResetPasswordForm;

class UsersController extends \app\addons\Controller {

    public function behaviors() {
        //actionResetpassword
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'except' => ['resetpassword'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionList() {

        $userQ = User::find()->andWhere('organization_id = ' . Yii::$app->user->identity->organization_id)
                ->andWhere('user_id != ' . Yii::$app->user->identity->user_id);
        $dataProvider = new ActiveDataProvider([
            'query' => $userQ,
            'pagination' => ['pagesize' => 10]
        ]);


        return $this->render('list', [
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionSave() {
        $model = new UserForm();
        $user = false;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $security = new Security();
            if (!empty($model->user_id) && is_numeric($model->user_id)) {
                $user = User::findOne(['user_id' => $model->user_id]);
            } else {
                $user = new User();
            }

            $user->first_name = $model->first_name;
            $user->last_name = $model->last_name;
            $user->timezone = $model->timezone;
            $user->email = $model->email;
            $user->organization_id = Yii::$app->user->identity->organization_id;
            if (!empty($model->password)) {
                $user->password = $security->generatePasswordHash($model->password);
            }
            $user->save();
            return $this->redirect('list');
        } else {
            return $this->render('create', array('model' => $model));
        }
    }

    public function actionCreate() {

        $model = new UserForm();

        $model->timezone = 'America/New_York';
        return $this->render('create', array('model' => $model));
    }

    public function actionMyprofile()
    {

        return Yii::$app->runAction('users/edit');
    }
    
    public function actionEdit() {

        $model = new UserForm();
        
        $listoftimezones = [];


        if (null == Yii::$app->request->get('user_id') || Yii::$app->request->get('user_id') == '') {
            $user_id = Yii::$app->user->identity->user_id;
        } else {
            $user_id = Yii::$app->request->get('user_id');
        }

        $user = User::find()->
                        andWhere('user_id = ' . $user_id)->
                        andWhere('organization_id =' . Yii::$app->user->identity->organization_id)->one();
        if ($user === null) {
            throw new \yii\web\HttpException(403, 'You have no permissions to edit the user');
        }

        $model->first_name = $user->first_name;
        $model->last_name = $user->last_name;
        $model->timezone = $user->timezone;
        $model->email = $user->email;
        $model->user_id = $user_id;


        return $this->render('create', array('model' => $model));
    }

    public function actionResetpassword() {
        $token = Yii::$app->request->get('token_key');
        $model = new ResetPasswordForm();
        $security = new Security();

        if (($token !== null && $token !== '') || \Yii::$app->session->hasFlash('info')) {

            $tokenModel = new Token();
            $userToken = $tokenModel->find()->andFilterWhere(['=', 'token_key', $token])->one();

            if ($userToken !== null && $tokenModel->getIsExpired()) {

                if (Yii::$app->request->isPost) {

                    if ($model->load(Yii::$app->request->post())) {
                        if ($model->validate()) {

                            $userToken->user->password = $security->generatePasswordHash($model->password);
                            $userToken->user->md5password = md5($model->password);
                            $userToken->user->save();

                            \Yii::$app->session->setFlash('info', \Yii::t('app', 'Your password has been successfully changed'));
                            $userToken->delete();
                            return $this->redirect('resetpassword');
                        }
                    }
                }
            } else {
                \Yii::$app->session->setFlash('info', \Yii::t('app', 'Token is wrong or expired'));
            }
        } else {
            \Yii::$app->session->setFlash('info', \Yii::t('app', 'Access Denied'));
        }

        return $this->render('resetpassword', [
                    'model' => $model,
        ]);
    }

    public function actionDelete() {
        if (null !== Yii::$app->request->get('user_id') && Yii::$app->request->get('user_id') !== '') {
            $user_id = Yii::$app->request->get('user_id');
            $user = User::find()->
                            andWhere('user_id = ' . $user_id)->
                            andWhere('organization_id =' . Yii::$app->user->identity->organization_id)->one();
            if ($user === null) {
                throw new \yii\web\HttpException(403, 'You have no permissions to edit the user');
            }
            return $this->redirect('list');
        }
    }

    public function actionUploadanimals() {

        $model = new CsvuploadForm();

        if (Yii::$app->request->isPost) {


            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $animalscsv = UploadedFile::getInstance($model, 'animalscsv');
                $userId = $model->user_id;

                $newFilePath = Yii::$app->params['tempdir'] . "/{$animalscsv->name}";
                $uploadSuccess = $animalscsv->saveAs($newFilePath);
                if (!$uploadSuccess) {
                    throw new CHttpException('Error uploading file.');
                }
                $animalsList = array_map('str_getcsv', file($newFilePath));

                foreach (array_slice($animalsList, 1) as $animal) {
                    $usersDb = new Animals();
                    $usersDb->saveAnimals($animal, $userId);
                }
            }

            //echo '<pre>';
            //print_r($animalsList);
            //echo '</pre>';
            //$animlasDB = new Animals();



            unlink($newFilePath);

            /* if ($model->validate()) {
              $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
              } */
        } elseif (null != Yii::$app->request->get('user_id') && is_numeric(Yii::$app->request->get('user_id'))) {
            $user_id = Yii::$app->request->get('user_id');

            $model->user_id = $user_id;
            return $this->render('uploadanimals', array('model' => $model));
        } else {

            Yii::error("Tried dividing by zero.");
        }
    }

}
