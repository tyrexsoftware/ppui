<?php

namespace app\addons\helpers;

use yii;
use yii\i18n\Formatter;
use app\models\Applications2organization;

class GeneralHelper extends \yii\base\Module {

    public static function getBodyPartById($id) {

        $settings_xml = simplexml_load_file(Yii::getAlias('@app') . '/config/bodyparts_map.xsd');
        return (string) $settings_xml->pimate->bodypart[$id - 1]['value'];
    }

    public static function getViewById($id) {

        $settings_xml = simplexml_load_file(Yii::getAlias('@app') . '/config/bodyparts_map.xsd');
        return (string) $settings_xml->pimate->bodypart[$id - 1]['view'];
    }

    public static function observationSearchHelper($model, $query, $values = false) {

        if ($values === false) {
            if (\Yii::$app->user->identity->is_manager) {
                $query->andFilterWhere([\app\models\Observations::tableName() . '.' . 'organization_id' => \Yii::$app->user->identity->organization_id]);
            } else {
                $query->andFilterWhere([\app\models\Observations::tableName() . '.' . 'observer_id' => \Yii::$app->user->identity->id]);
            }
        } else {
            if (!empty($values)) {

                $safeAttributes = $model->safeAttributes();

                foreach ($values as $key => $searchparam) {

                    if (in_array($key, $safeAttributes)) {
                        if ($key === 'observation_date' && $searchparam !== '') {
                            $split_date = preg_split('/-/', $searchparam);
                            $formatter = new Formatter();

                            $mindate = $formatter->asTimestamp(trim($split_date[0]));
                            $maxdate = $formatter->asTimestamp(trim($split_date[1]));

                            $query->andFilterWhere(['>', \app\models\Observations::tableName() . '.' . $key, $mindate]);
                            $query->andFilterWhere(['<', \app\models\Observations::tableName() . '.' . $key, $maxdate]);
                        } else {
                            if (!empty($searchparam) && (strstr($searchparam, '*') != false || strstr($searchparam, '_') != false)) {

                                $searchparam = str_replace('*', '%', $searchparam);

                                $query->andFilterWhere(['like', \app\models\Observations::tableName() . '.' . $key, $searchparam, false]);
                            } else {

                                $query->andFilterWhere(['=', \app\models\Observations::tableName() . '.'
                                    . '' . $key, $searchparam]);
                            }
                        }
                    }
                }
            }
        }
        return $query;
    }

    public static function getNovelTestScore($value) {
        $score = 3;
        if ($value <= 10) {
            $score = 1;
        } elseif ($value > 11 && $value <= 179) {
            $score = 1;
        }
        return $score;
    }

    public static function cleansting($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function arrayCartesianProduct($arrays) {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++) {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn - 1); $j >= 0; $j --) {
                if (next($arrays[$j]))
                    break;
                elseif (isset($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

}
