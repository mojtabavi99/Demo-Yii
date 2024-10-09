<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Student $model */
/** @var common\models\School $schoolList */

$this->title = Yii::t('app', 'ویرایش : {name}', [
    'name' => $model->user->name . ' ' . $model->user->lastname ,
]);
$this->params['breadcrumbs'][] = Yii::t('app', '/ ویرایش');
?>
<div class="student-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'user' => $user,
        'model' => $model,
        'schoolList' => $schoolList,
    ]) ?>

</div>
