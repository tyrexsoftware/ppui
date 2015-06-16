<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property integer $organization_id
 * @property string $organization_name
 * @property string $organization_contactemail
 * @property integer $address_id
 * @property integer $status
 */
class Organization extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'organization';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['organization_name', 'organization_contactemail', 'status'], 'required'],
            [['address_id', 'status', 'organization_id'], 'integer'],
            [['organization_name', 'organization_contactemail'], 'string', 'max' => 64]
        ];
    }

    public function getUsers() {
        return $this->hasMany(User::className(), ['organization_id' => 'organization_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'organization_id' => Yii::t('app', 'Organization ID'),
            'organization_name' => Yii::t('app', 'Organization Name'),
            'organization_contactemail' => Yii::t('app', 'Organization Contact\'s email'),
            'address_id' => Yii::t('app', 'Address ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getOrganizationStateByUser($user_id) {
        return $this->find()->joinWith('users')->where(['user_id' => $user_id])->one();
    }

    public function getUserapplications() {

        return $this->hasMany(Applications::className(), ['applications_id' =>
                    'applications_id'])->viaTable('applications2organization', ['organization_id' => 'organization_id']);
    }

    public function getUsersByOrganization() {
        return $this->hasMany(User::className(), ['organization_id'=>'organization_id']);
    }

}
