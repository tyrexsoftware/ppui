<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $email
 * @property integer $organization_id
 * @property integer $address_id
 * @property integer $lastlogin
 * @property string $token
 * @property integer $tokengenerationdate
 * @property file $animalscsv
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //  [['first_name', 'last_name', 'password', 'email', 'organization_id', 'address_id'], 'required'],
            [['user_id', 'organization_id', 'address_id', 'lastlogin', 'tokengenerationdate'], 'integer'],
            [['first_name', 'last_name', 'password', 'md5password', 'email', 'access_token'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {

        return parent::beforeSave($insert);
    }

    public function getOrganizationByUser($user_id) {

        return $this->find()->select('organization_id')->where('user_id=' . $user_id)->one();
    }

    public function fields() {

        $fields = parent::fields();
        if (Yii::$app->urlManager->parseRequest(Yii::$app->request)[0] !== 'restweb/login') {
            unset($fields['access_token']);
            unset($fields['md5password']);
            unset($fields['tokengenerationdate']);
        }
        unset($fields['password']);
        unset($fields['address_id']);
        unset($fields['auth_key']);
        return $fields;
    }

    public function getUserorganization() {

        return $this->hasOne(Organization::className(), ['organization_id' => 'organization_id']);
    }

    public function getOrganizationusers() {
        return $this->hasMany(User::className(), ['organization_id' => 'organization_id'])
                        ->viaTable('organization', ['organization_id' => 'organization_id']);
    }

    public function getUserapplications() {

        return $this->hasMany(Applications::className(), ['applications_id' =>
                    'applications_id'])->viaTable('applications2organization', ['organization_id' => 'organization_id']);
    }

    public function restAuth($username, $password) {

        $security = new Security ();
        $user = $this->find()->where(['email' => $username])->one();

        if (null == $user) {
            throw new \yii\web\HttpException(403, 'Invalid Username or Password');
        }


        if ($security->validatePassword($password, $user->password)) {

            $user->tokengenerationdate = time();
            $user->lastlogin = time();
            $user->access_token = md5(uniqid(mt_rand(), true));
            $user->update();
            return $user->user_id;
        } else {
            throw new \yii\web\HttpException(403, 'Invalid Username or Password');
        }
    }

    public function extraFields() {

        return preg_split("/,/", Yii::$app->request->get('expand'));
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    public function getId() {
        return $this->user_id;
    }

    public function getAuthKey() {
        return $this->authKey;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    function attributeLabels() {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password' => 'Password',
            'email' => 'Email',
            'organization_id' => 'Organization ID',
            'address_id' => 'Address ID',
            'lastlogin' => 'Date of last login',
            'access_token' => 'Unique Token',
            'tokengenerationdate' => 'Token Generation Date',
        ];
    }

}
