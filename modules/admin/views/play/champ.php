<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Игры. Создай игру';
?>
<div class="play-index container">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать Игру', ['create'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Список Команд', ['team/index'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Добавить команду', ['team/create'], ['class' => 'btn btn-danger']) ?>
    </p>

    <h4>Выберите чемпионат:</h4>
    <a href="<?= Url::to(['/admin/play/champ', 'id' => 1]) ?>"><?= Html::img('@web/img/champ/1.png', ['alt' => 'Испания']) ?></a>
    <a href="<?= Url::to(['/admin/play/champ', 'id' => 2]) ?>"><?= Html::img('@web/img/champ/2.png', ['alt' => 'Англия']) ?></a>
    <a href="<?= Url::to(['/admin/play/champ', 'id' => 3]) ?>"><?= Html::img('@web/img/champ/3.png', ['alt' => 'Россия']) ?></a>

    <?= GridView::widget([
        'pager' => [
            'firstPageLabel' => '<<<',
            'lastPageLabel'  => '>>>'
        ],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'date',
                'value' => function($data){
                    return  Yii::$app->formatter->asDatetime($data['date']);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'delay',
                'value' => function($data){
                    return $data['delay'] == 0 ? 'Нет' : "<span class='text-danger'>Да</span>";
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'league_id',
                'value' => function($data){
                    $x = $data['league']['league'];
                    return "<span class='text-danger'>$x</span>";
                },
                'format' => 'html',
            ],
            'h_tid_yellow_cart',
            'h_tid_offside',
            'h_tid_shot_on_goal',
            'h_tid_foul',
            'h_tid_corner',
            'h_tid_posses',
            'home_score_full',
            [
                'attribute' => 'home_team_id',
                'value' => function($data){
                    $x = $data['teamHome']['team_name'];
                    return "<span class='text-primary'>$x</span>";
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'away_team_id',
                'value' => function($data){
                    $x = $data['teamAway']['team_name'];
                    return "<span class='text-primary'>$x</span>";
                },
                'format' => 'html',
            ],
            'away_score_full',
            'a_tid_posses',
            'a_tid_corner',
            'a_tid_foul',
            'a_tid_shot_on_goal',
            'a_tid_offside',
            'a_tid_yellow_cart',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <div class="panel panel-default legend">
        <span class="legend-stat">Хозяева</span> - домашняя команда; <span class="legend-stat">Гости</span> - гостевая команда;
        <span class="legend-stat">Голы х</span> - голы забитые домашней командой;
        <span class="legend-stat">Голы г</span> - голы забитые гостевой командой;<br>
        <span class="legend-stat">% вл. дом</span> - владение мячом домашней команды;
        <span class="legend-stat">% вл. гос</span> - владение мячом гостевой команды;
        <span class="legend-stat">Удары д.</span> - удары в створ домашней команды;
        <span class="legend-stat">Удары г.</span> - удары в створ гостевой команды;<br>
        <span class="legend-stat">Фолы д.</span> - Фолы домашней команды;
        <span class="legend-stat">Фолы г.</span> - Фолы гостевой команды;
        <span class="legend-stat">Угл д.</span> - Угловые домашней команды;
        <span class="legend-stat">Угл г.</span> - Угловые гостевой команды;<br>
        <span class="legend-stat">Оффсайды д.</span> - Оффсайды домашней команды;
        <span class="legend-stat">Оффсайды г.</span> - Оффсайды гостевой команды;
        <span class="legend-stat">Ж.к. д.</span> - Жёлтые карточки домашней команды;
        <span class="legend-stat">Ж.к. г.</span> - Жёлтые карточки гостевой команды;<br>
        <span class="legend-stat">К.к. д.</span> - Красные карточки домашней команды;
        <span class="legend-stat">К.к. г.</span> - Красные карточки гостевой команды;
    </div>
</div>
