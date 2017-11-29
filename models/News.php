<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $author_id
 * @property integer $category_id
 * @property string $title
 * @property string $body
 * @property integer $status
 * @property integer $geo_id
 * @property integer $priority
 *
 * @property BranchNews[] $branchNews
 * @property Branch[] $branches
 * @property Country $geo
 * @property User $author
 * @property Category $category
 */
class News extends \yii\db\ActiveRecord
{
    public $bs;
    //const STATUSES = [0 => 'Удалён', 1 => 'Не активный', 2 => 'Активный'];
    //const PRIORITIES = [1 => 'Обычный', 2 => 'Важно', 3 => 'Критично'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['title', 'body', 'geo_id', 'bs'], 'required', 'on' => 'create'],
            [['title', 'body', 'geo_id', 'bs'], 'required', 'on' => 'update'],
            //['bs', 'required'],
            [['author_id', 'category_id', 'status', 'geo_id', 'priority'], 'integer'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['geo_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
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
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'status' => Yii::t('app', 'Status'),
            'geo_id' => Yii::t('app', 'Geo ID'),
            'priority' => Yii::t('app', 'Priority'),
            'branches' => Yii::t('app', 'Branches'),
            'bs' => Yii::t('app', 'Branches'),
            'time' => Yii::t('app', 'Time'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->time = time();
                $this->author_id = Yii::$app->user->id;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchNews()
    {
        return $this->hasMany(BranchNews::className(), ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable('branch_news', ['news_id' => 'id']);
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
