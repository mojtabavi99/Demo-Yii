<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\School $model */

$this->title = Yii::t('app', 'ویرایش : {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست مدارس'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'ویرایش');
?>
<div class="school-update">

    <h1 class="mb-5"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
