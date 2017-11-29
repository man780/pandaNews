<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Category;
use app\models\News;
use app\models\Country;
use app\models\User;
use app\models\Branch;
/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
$addButton = Html::a(Yii::t('app', 'Create News'), ['create'], ['class' => 'btn btn-success']);

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:20px'],
    ],
    [
        'attribute' => 'author_id',
        'filter' => User::getList(),
        'content'=>function($data){
            return $data->author->name;
        },
    ],
    [
        'attribute' => 'category_id',
        'filter' => Category::getList(),
        'content'=>function($data){
            return $data->getCategoryTitle();
        },
    ],
    'title',
    //'body:ntext',
    [
        'attribute'=>'status',
        'filter' => News::getStatuses(),
        'content'=>function($data){
            return News::getStatuses($data->status);
        },

    ],
    [
        'attribute' => 'geo_id',
        'filter' => Country::getList(),
        'content'=>function($data){
            return $data->geo->cc_iso;
        },
    ],
    [
        'attribute'=>'priority',
        'filter' => News::getPriorities(),
        'content'=>function($data){
            return News::getPriorities($data->priority);
        },
    ]
];

$branches = [
    'label' => Yii::t('app','Branches'),
    'format' => 'html',
    'attribute'=>'branch_id',
    'filter' => Branch::getList(),
    'value' => function($data) {
        foreach ($data->branches as $branch) {
            $branchesArr[] = $branch->title;
        }
        if(is_array($branchesArr))
            return "<span class='branches'>".implode("</span> <span class='branches'>", $branchesArr)."</span>";
    },
];
$buttons = [
    'class' => 'yii\grid\ActionColumn',
    'headerOptions' => ['style' => 'width:70px'],
];
if(Yii::$app->user->identity->role == 3){
    $addButton = '';
    $branches = [];
    $buttons = [];
}else{
    array_push($columns, $branches);
    array_push($columns, $buttons);
}

//vd($columns);
?>
<style>
    .branches{
        background-color: #d9edf7;
        color: #31708f;
        border-radius: 5px;
        padding: 3px 5px;
        margin: 1px;
    }
</style>
<div class="news-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $addButton ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-condensed'],
        'columns' => $columns/*[
            $columns,
            $branches,
            $buttons,
        ],*/
    ]); ?>
</div>
