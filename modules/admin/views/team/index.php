<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Команды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index container">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Добавить команду', ['create'], ['class' => 'btn btn-danger']) ?>
            <?= Html::a('Список игр', ['play/index'], ['class' => 'btn btn-danger']) ?>
            <?= Html::a('Создать Игру', ['play/create'], ['class' => 'btn btn-danger']) ?>
        </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'team_name',
                    'value' => function($data){
                        $x = $data['team_name'];
                        return "<span class='text-info'>$x</span>";
                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'team_embl',
                    'value' => function($data){
                        return Html::img($data['team_embl']);

                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'team_stadium',
                    'value' => function($data){
                        $x = $data['team_stadium'];
                        return "<span class='text-info'>$x</span>";
                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'team_league',
                    'value' => function($data){
                        $x = $data['league']['league'];
                        return "<span class='text-info'>$x</span>";
                    },
                    'format' => 'html',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
</div>