<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "staff".
 *
 * @property integer $staff_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $creation_date
 * @property string $update_date
 */
class Staff extends \yii\db\ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'staff';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['first_name', 'last_name', 'email', 'password', 'creation_date', 'update_date'], 'required'],
            [['creation_date', 'update_date'], 'safe'],
            [['first_name', 'last_name', 'email', 'password'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_id' => Yii::t('app', 'Staff ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'creation_date' => Yii::t('app', 'Creation Date'),
            'update_date' => Yii::t('app', 'Update Date'),
        ];
    }

    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    public function getId() {
        return $this->staff_id;
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return $this->authKey;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function checkStaffAccess($username, $password) {
        $user = static::find()->where(['email' => $username])->one();
        if (null === $user) {
            return false;
        }
        $security = new Security();
        if ($security->validatePassword($password, $user->password)) {
            return $user;
        } else {
            return false;
        }
    }

}
