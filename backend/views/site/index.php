<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\School;
use common\models\Student;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\StudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست دانش آموزان');
?>
<div class="student-index">

    <h1 class="my-5"><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'content' => function ($model) {
                    return $model->user->name;
                }
            ],
            [
                'attribute' => 'lastname',
                'content' => function ($model) {
                    return $model->user->lastname;
                }
            ],
            'national_id',

            [
                'attribute' => 'field_of_study',
                'format' => 'html',
                'filter' => Student::getFields(),
                'content' => function ($model) {
                    return match ($model->field_of_study) {
                        Student::FIELD_NONE => '<p>سایر موارد</p>',
                        Student::FIELD_MATHEMATICS => '<p>ریاضی</p>',
                        Student::FIELD_SCIENCES => '<p>تجربی</p>',
                        Student::FIELD_HUMANITIES => '<p>انسانی</p>',
                        Student::FIELD_TECHNICAL => '<p>فنی و حرفه‌ای</p>',
                        Student::FIELD_ARTS => '<p>هنر</p>',
                        default => '<p>نا مشخص</p>',
                    };
                }
            ],
            [
                'attribute' => 'grade',
                'format' => 'html',
                'filter' => Student::getGrades(),
                'content' => function ($model) {
                    return match ($model->grade) {
                        Student::GRADE_ELEMENTARY_SCHOOL => '<p>دبستان</p>',
                        Student::GRADE_MIDDLE_SCHOOL => '<p>راهنمایی</p>',
                        Student::GRADE_HIGH_SCHOOL => '<p>دبیرستان</p>',
                        default => '<p>نا مشخص</p>',
                    };
                }
            ],
            [
                'attribute' => 'school_id',
                'format' => 'html',
                'filter' => ArrayHelper::map(School::find()->all(), 'id', 'name'),
                'content' => function ($model) {
                    return $model->school->name;
                }
            ],
            'academic_year',
            [
                'attribute' => 'status',
                'format' => 'html',
                'filter' => Student::getStatus(),
                'content' => function ($model) {
                    return match ($model->status) {
                        Student::STATUS_REGISTERED => '<p>ثبت اطلاعات</p>',
                        Student::STATUS_APPROVED => '<p>تایید شده</p>',
                        Student::STATUS_REJECTED => '<p>رد شده</p>',
                        Student::STATUS_CANCELLED => '<p>انصراف داده</p>',
                        Student::STATUS_COMPLETION => '<p>تکمیل پرونده</p>',
                        Student::STATUS_PENDING => '<p>در انتظار پرداخت</p>',
                        default => '<p>نا مشخص</p>',
                    };
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'جزئیات'), ['/student/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['/student/update', 'id' => $model->user_id], ['class' => 'btn btn-primary']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'حذف'), ['/student/delete', 'id' => $model->user_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>


</div>
