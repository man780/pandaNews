<?php

use app\models\News;
use app\models\Offer;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Panda News';
$newsList = News::find()->orderBy(['id' => SORT_DESC])->limit(3)->all();
$offersList = Offer::find()->orderBy(['id' => SORT_DESC])->limit(3)->all();
?>
<div class="site-index">

    <div class="jumbotron">
        <p class="lead">Вас приветствует</p>
        <h1>Panda News</h1>

        <p><a class="btn btn-lg btn-success" href="#">Привет <?=Yii::$app->user->identity->name?></a></p>
    </div>

    <div class="body-content">


        <div class="row">
            <h2 align="center">Последние новости</h2>
            <?foreach ($newsList as $news):?>
            <div class="col-lg-4">
                <h2><?=$news->title?></h2>

                <p><?=$news->body?></p>

                <p><a class="btn btn-default" href="<?=Url::toRoute(['/news/view', 'id' => $news->id])?>">Просмотр &raquo;</a></p>
            </div>
            <?endforeach;?>
        </div>

        <div class="row">
            <h2 align="center">Последние офферы</h2>
            <?foreach ($offersList as $offer):?>
                <div class="col-lg-4">
                    <h2><?=$offer->name?></h2>

                    <p><?=$offer->body?></p>

                    <p><a class="btn btn-default" href="<?=Url::toRoute(['/news/view', 'id' => $offer->id])?>">Просмотр &raquo;</a></p>
                </div>
            <?endforeach;?>
        </div>

    </div>
</div>
