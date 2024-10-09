<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Student;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Student $model */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var common\models\School $schoolList */


$this->registerCssFile('@web/css/date-picker.css');
$this->registerJsFile('@web/js/date-picker.js');

$this->registerJs('
jalaliDatepicker.startWatch({
  minDate: "attr",
  maxDate: "attr"
});
', View::POS_END);
?>

<div class="student-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($user, 'lastname')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($user, 'mobile')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'national_id')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mb-3 field-course-register_start required">
                <label for="birthdate" class="form-label">تاریخ تولد</label>
                <input type="text" data-jdp class="form-control" id="birthdate" name="Student[birthdate]"
                       value="<?= Gadget::convertToEnglish(Jdf::jdate('Y/m/d', $model->birthdate)) ?>">
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'field_of_study')->dropDownList(Student::getFields(), [
                'prompt' => 'لطفا انتخاب کنید',
            ]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'grade')->dropDownList(Student::getGrades(), [
                'prompt' => 'لطفا انتخاب کنید',
            ]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'school_id')->dropDownList($schoolList, [
                'prompt' => 'لطفا انتخاب کنید',
            ]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'academic_year')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'status')->dropDownList(Student::getStatus(), [
                'prompt' => 'لطفا انتخاب کنید',
            ]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
