<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "address_book".
 *
 * @property integer $address_id
 * @property integer $company_id
 * @property integer $user_id
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property integer $country_id
 * @property string $phone
 * @property string $fax
 * @property string $websiteurl
 * @property string $contact_email
 */
class AddressBook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'user_id', 'country_id'], 'integer'],
            [['address', 'city', 'state', 'postal_code', 'country_id', 'phone', 'fax', 'websiteurl', 'contact_email'], 'required'],
            [['address', 'city', 'state', 'postal_code', 'phone', 'fax', 'websiteurl', 'contact_email'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => 'Address ID',
            'company_id' => 'Company ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => 'Postal Code',
            'country_id' => 'Country ID',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'websiteurl' => 'Websiteurl',
            'contact_email' => 'Contact Email',
        ];
    }
}
