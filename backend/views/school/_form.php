<?php

use common\models\School;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\School $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="school-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gender')->dropDownList(School::getGenderList()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
