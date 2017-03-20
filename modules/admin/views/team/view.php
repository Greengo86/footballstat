<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Team */

$this->title = $model->team_name;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="team-view container">


    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать Игру', ['create'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Список Команд', ['team/index'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Добавить команду', ['team/create'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'team_id',
            'team_name',
            [
                'label' => 'Эмблема',
                'value' => Html::img($model['team_embl']),
                'format' => 'html',
            ],
            'team_stadium',
            [
                'label' => 'Чемпионат',
                'value' => $model['league']['league'],
            ],
        ],
    ])
    ?>

    <p>
        <?= Html::a('Править', ['update', 'id' => $model->team_id], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->team_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


</div>


