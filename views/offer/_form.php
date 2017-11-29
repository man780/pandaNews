<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Branch;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="offer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->dropDownList($categories); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num')->textInput() ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'land')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preland')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'status')->dropDownList($statuses) ?>

    <?
    echo $form->field($model, 'geo_id')->widget(Select2::classname(), [
        'data' => $geos,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите гео ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?//= $form->field($model, 'priority')->dropDownList($priorities) ?>

    <?= $form->field($model, 'bs')->widget(Select2::className(), [
        'name' => Yii::t('app', 'bs'),
        'data' => Branch::getList(),
        'options' => [
            'multiple' => true
        ],
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ?
                    Yii::t('app', 'Create') :
                    Yii::t('app', 'Update'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
