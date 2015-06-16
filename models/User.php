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
 * @property string $access_token
 * @property string $tokengenerationdate
 * @property integer $lastlogin
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
            [['first_name', 'last_name', 'password', 'email', 'organization_id'], 'required'],
            [['organization_id', 'address_id', 'is_manager', 'lastlogin'], 'integer'],
            [['first_name', 'last_name', 'password', 'email', 'auth_key', 'access_token', 'tokengenerationdate'], 'string', 'max' => 64]
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    public function getUserapplications() {

        return $this->hasMany(Applications::className(), ['applications_id' =>
                    'applications_id'])->viaTable('applications2organization', ['organization_id' => 'organization_id']);
    }

    public function getId() {
        return $this->user_id;
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public static function setAuthKey($user) {
        $security = new Security();

        $user->auth_key = md5(uniqid(mt_rand(), true));
        $user->save();
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    public static function checkUserAccess($username, $password) {
        $user = static::find()->where(['email' => $username])->one();
        if (null === $user) {
            return false;
        }
        $security = new Security();
        if ($security->validatePassword($password, $user->password)) {
            self::setAuthKey($user);
            return $user;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
            'organization_id' => Yii::t('app', 'Organization ID'),
            'address_id' => Yii::t('app', 'Address ID'),
            'access_token' => Yii::t('app', 'Access Token'),
            'tokengenerationdate' => Yii::t('app', 'Tokengenerationdate'),
            'lastlogin' => Yii::t('app', 'Lastlogin'),
        ];
    }

}
