<?php

namespace app\addons\helpers;

use app\models\Observations;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use app\models\NovelobjectActions;
use app\addons\helpers\GeneralHelper;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SearchObservation extends Observations {

    /**
     * @var string Name
     */
    public $name;

    /**
     * @var string Surname
     */
    public $surname;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // String
            [['location', 'animal_id'], 'string'],
            [['observation_date'], 'safe',],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params Search params
     *
     * @return ActiveDataProvider DataProvider
     */
    public function search($params, $appkey, array $joinWith = []) {
        $query = self::find();

        $query->andFilterWhere([Observations::tableName() . '.' . 'appkey' => $appkey]);

        $query = GeneralHelper::observationSearchHelper($this, $query);

        if (!empty($joinWith)) {
            $query->joinWith($joinWith);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['observation_date' => SORT_DESC]],
            'pagination' => ['pagesize' => 10],
        ]);


        if (!($this->load($params) || !$this->validate())) {

            return $dataProvider;
        }

        $query = GeneralHelper::observationSearchHelper($this, $query, $params['SearchObservation']);

        return $dataProvider;
    }

}
