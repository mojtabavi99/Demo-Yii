<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var common\models\SignupForm $userModel */
/** @var common\models\Student $studentModel */
/** @var common\models\School $schoolList */

use common\models\Student;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

$this->title = 'ثبت نام دانش آموز';

$this->registerCssFile('@web/css/date-picker.css');
$this->registerJsFile('@web/js/date-picker.js');

$this->registerJs('
jalaliDatepicker.startWatch({
  minDate: "attr",
  maxDate: "attr"
});
', View::POS_END);
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-7 pt-5">
            <h1>ثبت دانش آموز</h1>

            <p>لطفا برای ثبت نام تمامی اطلاعات زیر را با دقت وارد کنید</p>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($userModel, 'name')->textInput() ?></div>
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($userModel, 'lastname')->textInput() ?></div>
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($userModel, 'mobile')->textInput() ?></div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($studentModel, 'national_id')->textInput() ?></div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="mb-3 field-course-register_start required">
                        <label for="birthdate" class="form-label">تاریخ تولد</label>
                        <input type="text" data-jdp class="form-control" id="birthdate" name="Student[birthdate]" placeholder="مثال: ۱۳۸۰/۰۱/۰۱">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <?= $form->field($studentModel, 'field_of_study')->dropDownList(Student::getFields(), [
                        'prompt' => 'لطفا انتخاب کنید',
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <?= $form->field($studentModel, 'grade')->dropDownList(Student::getGrades(), [
                        'prompt' => 'لطفا انتخاب کنید',
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <?= $form->field($studentModel, 'school_id')->dropDownList($schoolList, [
                        'prompt' => 'لطفا انتخاب کنید',
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <?= $form->field($studentModel, 'academic_year')->textInput([
                        'placeholder' => 'مثال: ۰۴ - ۱۴۰۳'
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('ثبت نام', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-5">
            <img class="w-100 h-auto d-block mx-auto" src="/upload/static/banner.jpg" alt="">
        </div>
    </div>
</div>
