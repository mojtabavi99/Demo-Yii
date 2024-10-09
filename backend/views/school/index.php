<?php

use common\models\School;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\SchoolSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست مدارس');
$this->params['breadcrumbs'][] = '/ ' . $this->title;
?>
<div class="school-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-5">
        <?= Html::a(Yii::t('app', 'افزودن مدرسه'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'gender',
                'format' => 'html',
                'filter' => School::getGenderList(),
                'value' => function ($model) {
                    return match ($model->gender) {
                        School::GENDER_MALE => 'پسرانه',
                        School::GENDER_FEMALE => 'دخترانه',
                    };
                }
            ],
            'name',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'جزئیات'), ['view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
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
