<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика матчей: ' . $play->league;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php debug($play) ?>
</div>
