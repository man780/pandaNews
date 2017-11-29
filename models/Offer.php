<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "offer".
 *
 * @property integer $id
 * @property integer $author_id
 * @property integer $category_id
 * @property string $name
 * @property integer $num
 * @property string $body
 * @property string $land
 * @property string $preland
 * @property integer $status
 * @property integer $geo_id
 * @property integer $priority
 *
 * @property BranchOffer[] $branchOffers
 * @property Branch[] $branches
 * @property Country $geo
 * @property User $author
 * @property Category $category
 */
class Offer extends \yii\db\ActiveRecord
{
    public $bs;
    /*const STATUSES = [0 => 'Удалён', 1 => 'Не активный', 2 => 'Активный'];
    const PRIORITIES = [1 => 'Обычный', 2 => 'важно', 3 => 'критично'];*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'num', 'geo_id', 'bs'], 'required', 'on' => 'create'],
            [['name', 'num', 'geo_id', 'bs'], 'required', 'on' => 'update'],
            [['author_id', 'category_id', 'num', 'status', 'geo_id', 'priority'], 'integer'],
            [['body'], 'string'],
            [['name', 'land', 'preland'], 'string', 'max' => 255],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['geo_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->dcreated = time();
                $this->author_id = Yii::$app->user->id;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_id' => Yii::t('app', 'Author ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'name' => Yii::t('app', 'Name Offer'),
            'num' => Yii::t('app', 'Num'),
            'body' => Yii::t('app', 'Body'),
            'land' => Yii::t('app', 'Land'),
            'preland' => Yii::t('app', 'Preland'),
            'status' => Yii::t('app', 'Status'),
            'geo_id' => Yii::t('app', 'Geo ID'),
            'priority' => Yii::t('app', 'Priority'),
            'branches' => Yii::t('app', 'Branches'),
            'bs' => Yii::t('app', 'Branches'),
            'dcreated' => Yii::t('app', 'Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchOffers()
    {
        return $this->hasMany(BranchOffer::className(), ['offer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable('branch_offer', ['offer_id' => 'id']);
    }

    public function getBranchesAll()
    {
        return ArrayHelper::map(Branch::find()->all(), 'id', 'title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeo()
    {
        return $this->hasOne(Country::className(), ['id' => 'geo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCategoryTitle()
    {
        $category = $this->category;
        return $category ? $category->title : '';
    }

    public function getStatuses($status = null){

        $statArr = [0 => 'Удалён', 1 => 'Не активный', 2 => 'Активный'];
        return (is_null($status))?$statArr:$statArr[$status];
    }

    public function getPriorities($priority = null){
        $priorArr = [1 => 'Обычный', 2 => 'Важно', 3 => 'Критично'];
        //$priorArr = self::PRIORITIES;
        return (is_null($priority))?$priorArr:$priorArr[$priority];
    }
}
