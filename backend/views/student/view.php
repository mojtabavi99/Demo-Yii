<?php

use common\components\Jdf;
use common\models\Student;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Student $model */

$this->title = $model->user->name . ' ' . $model->user->lastname;
$this->params['breadcrumbs'][] = '/ ' . $this->title;
YiiAsset::register($this);
?>
<div class="student-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-5">
        <?= Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'national_id',
            [
                'attribute' => 'birthdate',
                'value' => function ($model) {
                    return Jdf::jdate('Y/m/d', $model->birthdate);
                }
            ],
            [
                'attribute' => 'field_of_study',
                'value' => function ($model) {
                    return match ($model->field_of_study) {
                        Student::FIELD_NONE => 'سایر موارد',
                        Student::FIELD_HUMANITIES => 'انسانی',
                        Student::FIELD_MATHEMATICS => 'ریاضی',
                        Student::FIELD_SCIENCES => 'تجربی',
                        Student::FIELD_TECHNICAL => 'فنی و حرفه‌ای',
                        Student::FIELD_ARTS => 'هنر',
                    };
                }
            ],
            [
                'attribute' => 'grade',
                'value' => function ($model) {
                    return match ($model->grade) {
                        Student::GRADE_ELEMENTARY_SCHOOL => 'دبستان',
                        Student::GRADE_MIDDLE_SCHOOL => 'راهنمایی',
                        Student::GRADE_HIGH_SCHOOL => 'دبیرستان',
                    };
                }
            ],
            [
                'attribute' => 'school_id',
                'value' => function ($model) {
                    return $model->school->name;
                }
            ],
            'academic_year',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return match ($model->status) {
                        Student::STATUS_REGISTERED => 'ثبت اطلاعات',
                        Student::STATUS_APPROVED => 'تایید شده',
                        Student::STATUS_REJECTED => 'رد شده',
                        Student::STATUS_CANCELLED => 'انصراف داده',
                        Student::STATUS_COMPLETION => 'تکمیل پرونده',
                        Student::STATUS_PENDING => 'در انتظار پرداخت',
                    };
                }
            ],
        ],
    ]) ?>

</div>
