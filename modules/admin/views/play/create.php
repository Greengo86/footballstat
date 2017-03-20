<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Play */

$this->title = 'Создание матча';
$this->params['breadcrumbs'][] = ['label' => 'Plays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-create container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
