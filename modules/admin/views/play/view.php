<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Play */


$this->title = $model->teamHome->team_name . ' - ' . $model->teamAway->team_name . Yii::$app->formatter->asDatetime($model->date);
$this->params['breadcrumbs'][] = ['label' => 'Plays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="play-view container">

    <?php echo Html::button("<span class='glyphicon glyphicon-home' aria-hidden='true'></span>",
    ['class'=>'kv-action-btn',
    'onclick'=>"window.location.href = '" . 'index' . "';",
    'data-toggle'=>'tooltip',
    'title'=>'На главную админки',
    ]
    )?>

    <?php echo Html::button("<span class='glyphicon glyphicon-plus' aria-hidden='true'></span>",
        ['class'=>'kv-action-btn',
            'onclick'=>"window.location.href = '" . 'create' . "';",
            'data-toggle'=>'tooltip',
            'title'=> 'Создать матч'
        ]
    )?>

<!--    --><?php //echo Html::a('На Главную Админки', ['play/index'], ['btn btn-danger']); ?>
    <h1><?= ($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены ,что хотите удалить данную игру?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<!--    --><?php //var_dump($model) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'year',
            [
                'label' => 'Перенесён ли Матч',
                'type' => 'html',
                'value' => $model->delay == 0 ? 'Нет' : 'Да'
            ],
            [
                'label' => 'Дата',
                'type' => 'html',
                'value' => Yii::$app->formatter->asDatetime($model->date),
            ],
            [
                'label' => 'Лига',
                'type' => 'html',
                'value' => $model->getLeagueHtml($model->league_id),
            ],
            [
                'label' => 'Хозяева',
                'value' => $model['teamHome']['team_name'],
            ],
            [
                'label' => 'Гости',
                'value' => $model['teamAway']['team_name'],
            ],
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
