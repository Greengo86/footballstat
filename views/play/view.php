<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Play */

$this->title = $model->teamHome->team_name . ' - ' . $model->teamAway->team_name . '<br/>' . $model->date;
$this->params['breadcrumbs'][] = ['label' => 'Plays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-view">

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
            'year',
            'delay',
            'date',
            'league_id',
            'home_team_id',
            'away_team_id',
            'home_score_full',
            'away_score_full',
            'h_tid_posses',
            'a_tid_posses',
            'h_tid_shot_on_goal',
            'a_tid_shot_on_goal',
            'h_tid_foul',
            'a_tid_foul',
            'h_tid_corner',
            'a_tid_corner',
            'h_tid_offside',
            'a_tid_offside',
            'h_tid_yellow_cart',
            'a_tid_yellow_cart',
            'h_tid_red_cart',
            'a_tid_red_cart',
        ],
    ]) ?>

</div>
