<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Play */

$this->title = 'Create Play';
$this->params['breadcrumbs'][] = ['label' => 'Plays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
