<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\League */

$this->title = $model->teamHome->team_name . ' - ' . $model->teamAway->team_name . '<br/>' . $model->date;
$this->params['breadcrumbs'][] = ['label' => 'Leagues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="league-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены ,что хотите удалить данную игру?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'league',
        ],
    ]) ?>

</div>
