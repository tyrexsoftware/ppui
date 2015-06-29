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
    
    public function actionEthogrammanagement ()
    {
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
        
        $animals->orderBy(['name'=> 'SORT_ASC']);
        
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
    
    public function actionAddsyncanimals(){
        $animals = Yii::$app->request->post('animals');
        $users = Yii::$app->request->post('users');
        $applications = Yii::$app->request->post('applications');
        foreach ($animals as $animal) {
            AnimalsSync::deleteAll(['animal_id'=> $animal]);
        }
        $insert_array = \app\addons\helpers\GeneralHelper::arrayCartesianProduct([$animals, $users]);
        foreach ($insert_array as $data) {
            $syncAnimals = new AnimalsSync;
            $syncAnimals->animal_id = $data[0];
            $syncAnimals->user_id = $data[1];
            $syncAnimals->appkey = implode(',', $applications);
            $syncAnimals->save();
        }
        $reply = ['message'=>'success', 'jsaction'=>'reload'];
        \Yii::$app->response->format = 'json';
        return $reply;
    }
    public function actionComposeethogram() {
        
        if (Yii::$app->request->isPost) {
            switch(Yii::$app->request->post('section')){
                case 'container':
                    $model = new \app\models\EthogramContainer();
                    
                    
                    break;
                case 'behavior':
                    break;
            }
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
                    foreach ($deleteAnimals as $todeleteAnimal)
                    {
                    
                        $todeleteAnimal->delete();
                    }
                }

                foreach (array_slice($animalsList, 1) as $animal) {
                    $usersDb = new Animals();
                    if(Yii::$app->request->post('typeofupload') == 'merge'){
                       $merge = Animals::find()
                               ->andFilterWhere(['=', 'name', $animal[0]])  
                               ->andFilterWhere(['=', 'location', $animal[1]])  
                               ->andFilterWhere(['=', 'user_id', Yii::$app->user->identity->user_id])  
                               ->andFilterWhere(['=', 'organization_id', Yii::$app->user->identity->organization_id]);
                       if($merge->one()!==null)
                       {
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
