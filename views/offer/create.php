<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Offer */

$this->title = Yii::t('app', 'Create Offer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'geos' => $geos,
        'categories' => $categories,
        'statuses' => $statuses,
        'priorities' => $priorities,
    ]) ?>

</div>
