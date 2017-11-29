<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Branch;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$statuses = User::getStatuses();
$roles = User::getRoles();
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?//= $form->field($model, 'branch_id')->textInput() ?>
    <?= $form->field($model, 'branch_id')->widget(Select2::className(), [
        'name' => Yii::t('app', 'branch_id'),
        'data' => Branch::getList(),
        'options' => [
            //'multiple' => true
        ],
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList($statuses) ?>

    <?= $form->field($model, 'role')->dropDownList($roles) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create') , ['class' => 'btn btn-success' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
