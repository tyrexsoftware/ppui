<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "token".
 *
 * @property integer $token_id
 * @property integer $user_id
 * @property integer $token_type
 * @property integer $token_key
 * @property integer $created_at
 */
class Token extends \yii\db\ActiveRecord {

    const TYPE_RECOVERY = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'token';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'created_at',
            ],
        ];
    }
    
    public function getUser() 
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    public function getIsExpired() {
        switch ($this->token_type) {
            case self::TYPE_RECOVERY:
                $expirationTime = 86400;
                break;
            default:
                throw new \RuntimeException;
        }
        return ($this->created_at + $expirationTime) < time();
    }

    public function getUrl() {
        switch ($this->token_type) {
            case self::TYPE_RECOVERY:
                $route = '/users/resetpassword';
                break;
            default:
                throw new \RuntimeException;
        }
        return Url::to([$route, 'token_key' => $this->token_key], true);
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->setAttribute('token_key', \Yii::$app->security->generateRandomString());
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['token_key', 'created_at'], 'required'],
            [['created_at', 'user_id', 'token_type'], 'integer'],
            [['token_key'], 'string'],
            [['token_key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'token_id' => Yii::t('app', 'Token ID'),
            'token_key' => Yii::t('app', 'Token Key'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

}
