<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\forms\BehavioralSettingsForm;
use app\models\AnimalsSync;
use app\models\Animals;
use app\models\ApplicationSettings;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

class ApplicationsController extends \app\addons\Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
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
                    'savesettings' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionSettings() {

        $model = new BehavioralSettingsForm();
        $settings = ApplicationSettings::findAll(['organization_id' => Yii::$app->user->identity->organization_id]);

        foreach ($settings as $individualSetting) {
            $model->{$individualSetting->setting_name} = $individualSetting->setting_value;
        }

        return $this->render('settings', array('model' => $model));
    }

    private static function updateSetting(array $setting) {
        $db = ApplicationSettings::findOne(['appkey' => $setting[0], 'organization_id' => $setting[1], 'setting_name' => $setting[2]]);

        $db->setting_value = $setting[3];
        $db->save();
    }

    public function actionEthogramdata() {

        $ethogramcontainer = \app\models\EthogramContainer::find()
                ->where(['user_id' => 20])
                ->orderBy(['sort_order' => SORT_DESC])
                ->all();
        $ethogramArray = [];
        
        foreach ($ethogramcontainer as $subject) {
            $behavioursset = \app\models\EthogramElements::find()
                    ->where(['container_id' => $subject->container_id])
                    ->orderBy(['sort_order' => SORT_DESC])
                    ->all();
            $ethogramArray[$subject->sort_order]['name']=$subject->container_name;
            $ethogramArray[$subject->sort_order]['id']=$subject->container_id;
            $ethogramArray[$subject->sort_order]['sort_order']=$subject->sort_order;
            
            foreach ($behavioursset as $behaviour) {
                $ethogramArray[$subject->sort_order]['values'][$behaviour->element_id] = $behaviour->element_name;
                
            }
        }

        \Yii::$app->response->format = 'json';
        return $ethogramArray;
    }

    public function actionEthogrammanagement() {

        return $this->render('ethogrammanagement');
    }

    public function actionSavesettings() {
        $model = new BehavioralSettingsForm();



        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $animalscsv = UploadedFile::getInstance($model, 'xml_behavors');
            for ($i = 0; $i <= 3; $i++) {

                self::updateSetting(['alptest', Yii::$app->user->identity->organization_id, 'alopecia_status_' . $i, $model->{'alopecia_status_' . $i}]);
                self::updateSetting(['alptest', Yii::$app->user->identity->organization_id, 'alopecia_option_' . $i, $model->{'alopecia_option_' . $i}]);
                self::updateSetting(['alptest', Yii::$app->user->identity->organization_id, 'alopecia_color_' . $i, $model->{'alopecia_color_' . $i}]);
            }
            self::updateSetting(['bhvtest', Yii::$app->user->identity->organization_id, 'behavorial_observation_time', $model->behavorial_observation_time]);
            self::updateSetting(['nvobjtest', Yii::$app->user->identity->organization_id, 'novel_object_observation_time', $model->novel_object_observation_time]);
            return $this->redirect('settings');
        } else {

            return $this->render('settings', array('model' => $model));
        }
    }

    public function actionManageanimals() {
        $animals = Animals::find();


        if (Yii::$app->user->identity->is_manager == 1) {
            $animals->andFilterWhere(['=', Animals::tableName() . '.organization_id', Yii::$app->user->identity->organization_id]);
        } else {
            $animals->andFilterWhere(['=', AnimalsSync::tableName() . '.user_id', Yii::$app->user->identity->user_id]);
        }
        $animals->joinWith('syncedAnimals');

        $animals->orderBy(['name' => 'SORT_ASC']);

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pagesize' => 10],
            'query' => $animals,]);

        return $this->render('manageanimals', array('dataprovider' => $dataProvider));
    }

    public function actionUploadanimals() {

        $model = new \app\models\forms\AnimalsuploadForm();
        $model->typeofupload = ['replace'];

        return $this->render('animalsupload', array('model' => $model));
    }

    public function actionAddsyncanimals() {
        $animals = Yii::$app->request->post('animals');
        $users = Yii::$app->request->post('users');
        $applications = Yii::$app->request->post('applications');
        foreach ($animals as $animal) {
            AnimalsSync::deleteAll(['animal_id' => $animal]);
        }
        $insert_array = \app\addons\helpers\GeneralHelper::arrayCartesianProduct([$animals, $users]);
        foreach ($insert_array as $data) {
            $syncAnimals = new AnimalsSync;
            $syncAnimals->animal_id = $data[0];
            $syncAnimals->user_id = $data[1];
            $syncAnimals->appkey = implode(',', $applications);
            $syncAnimals->save();
        }
        $reply = ['message' => 'success', 'jsaction' => 'reload'];
        \Yii::$app->response->format = 'json';
        return $reply;
    }

    public function actionEthogramcontainer() {

        \Yii::$app->response->format = 'json';

        if (Yii::$app->request->isPost) {

            if (empty(Yii::$app->request->post('subject')) || is_null(Yii::$app->request->post('subject'))) {
                $reply = ['transaction' => 'error', 'message' => 'Subject is Empty'];
                return $reply;
            }

            if (!empty(Yii::$app->request->post('container_id')) || !is_null(Yii::$app->request->post('container_id'))) {
                $model = \app\models\EthogramContainer::findOne(['container_id' => Yii::$app->request->post('container_id')]);
            } else {
                $model = new \app\models\EthogramContainer();
            }


            $model->container_key = \app\addons\helpers\GeneralHelper::cleansting((string) Yii::$app->request->post('subject'));
            $model->container_name = Yii::$app->request->post('subject');
            $model->sort_order = Yii::$app->request->post('position');
            $model->user_id = Yii::$app->user->identity->user_id;

            $model->save();
            $reply = ['transaction' => 'success'];

            //Yii::$app->request->post('section');
        }
    }

    public function actionUploadfile() {

        if (Yii::$app->request->isPost) {

            $model = new \app\models\forms\AnimalsuploadForm();


            if ($model->validate()) {
                
                $animalscsv = UploadedFile::getInstance($model, 'animalscsv');


                $newFilePath = '\\' . "{$animalscsv->name}";
                $uploadSuccess = $animalscsv->saveAs($newFilePath);
                if (!$uploadSuccess) {
                    throw new CHttpException('Error uploading file.');
                }
                $animalsList = array_map('str_getcsv', file($newFilePath));

                if (Yii::$app->request->post('typeofupload') == 'replace') {

                    $deleteAnimals = AnimalsSync::find()->where(['user_id' => Yii::$app->user->identity->user_id])->all();
                    foreach ($deleteAnimals as $todeleteAnimal) {

                        $todeleteAnimal->delete();
                    }
                }

                foreach (array_slice($animalsList, 1) as $animal) {
                    $usersDb = new Animals();
                    if (Yii::$app->request->post('typeofupload') == 'merge') {
                        $merge = Animals::find()
                                ->andFilterWhere(['=', 'name', $animal[0]])
                                ->andFilterWhere(['=', 'location', $animal[1]])
                                ->andFilterWhere(['=', 'user_id', Yii::$app->user->identity->user_id])
                                ->andFilterWhere(['=', 'organization_id', Yii::$app->user->identity->organization_id]);
                        if ($merge->one() !== null) {
                            continue;
                        }
                    };

                    $usersDb->saveAnimals($animal, Yii::$app->user->identity->user_id);
                    $animalsSync = new AnimalsSync;
                    $animalsSync->user_id = Yii::$app->user->identity->user_id;
                    $animalsSync->animal_id = $usersDb->animal_id;
                    $animalsSync->appkey = 'all';
                    $animalsSync->save();
                }
                unlink($newFilePath);
                return \yii\helpers\Json::encode('success');
            }

            /* if ($model->validate()) {
              $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
              } */
        }
    }

}
