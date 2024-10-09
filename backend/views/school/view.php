<?php

use common\models\School;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\School $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست مدارس'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="school-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-5">
        <?= Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
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
            [
                'attribute' => 'gender',
                'value' => function ($model) {
                    return match ($model->gender) {
                        School::GENDER_MALE => 'پسرانه',
                        School::GENDER_FEMALE => 'دخترانه',
                    };
                }
            ],
            'name',
        ],
    ]) ?>

</div>
