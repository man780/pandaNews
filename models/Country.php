<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $cc_fips
 * @property string $cc_iso
 * @property string $tld
 * @property string $country_name
 *
 * @property News[] $news
 * @property Offer[] $offers
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_fips', 'cc_iso', 'tld', 'country_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cc_fips' => Yii::t('app', 'Cc Fips'),
            'cc_iso' => Yii::t('app', 'Cc Iso'),
            'tld' => Yii::t('app', 'Tld'),
            'country_name' => Yii::t('app', 'Country Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['geo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['geo_id' => 'id']);
    }

    public function getList()
    {
        $list = Country::find()->select(['id', 'cc_iso'])->all();
        return ArrayHelper::map($list, 'id', 'cc_iso');
    }
}
