<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
$items =
    [
        ['label' => 'Новости', 'url' => ['/news/index']],
        ['label' => 'Офферы', 'url' => ['/offer/index']],
    ];
if(Yii::$app->user->identity->role == 1){
    $users = ['label' => 'Пользователи', 'url' => ['user/index']];
    array_push($items, $users);
    $spr = ['label' => 'Справочники', 'url' => null,
            'items' => [
                ['label' => 'Страны', 'url' => '/country/index'],
                ['label' => 'Категории', 'url' => '/category/index'],
                ['label' => 'Отделы', 'url' => '/branch/index'],
            ],
    ];
    array_push($items, $spr);
}elseif(Yii::$app->user->identity->role == 2){
    $spr = ['label' => 'Справочники', 'url' => null,
        'items' => [
            ['label' => 'Страны', 'url' => '/country/index'],
            ['label' => 'Категории', 'url' => '/category/index'],
            ['label' => 'Отделы', 'url' => '/branch/index'],
        ],
    ];
    array_push($items, $spr);
}
$nav = Yii::$app->user->isGuest ? (
['label' => 'Вход', 'url' => ['/main/login']]
) : (
    '<li>'
    . Html::beginForm(['/main/logout'], 'post')
    . Html::submitButton(
        'Выход (' . Yii::$app->user->identity->name . ')',
        ['class' => 'btn btn-link logout']
    )
    . Html::endForm()
    . '</li>'
);
array_push($items, $nav);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,

            /*$nav,
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/main/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/main/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )*/

    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Panda Media <?= date('Y') ?></p>


    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
