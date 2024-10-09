<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\School $model */

$this->title = Yii::t('app', 'افزودن مدرسه');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست مدارس'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
