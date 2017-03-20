<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PlaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="play-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'year') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'league_id') ?>

    <?= $form->field($model, 'home_team_id') ?>

    <?php // echo $form->field($model, 'away_team_id') ?>

    <?php // echo $form->field($model, 'home_score_full') ?>

    <?php // echo $form->field($model, 'away_score_full') ?>

    <?php // echo $form->field($model, 'h_tid_posses') ?>

    <?php // echo $form->field($model, 'a_tid_posses') ?>

    <?php // echo $form->field($model, 'h_tid_shot_on_goal') ?>

    <?php // echo $form->field($model, 'a_tid_shot_on_goal') ?>

    <?php // echo $form->field($model, 'h_tid_foul') ?>

    <?php // echo $form->field($model, 'a_tid_foul') ?>

    <?php // echo $form->field($model, 'h_tid_corner') ?>

    <?php // echo $form->field($model, 'a_tid_corner') ?>

    <?php // echo $form->field($model, 'h_tid_offside') ?>

    <?php // echo $form->field($model, 'a_tid_offside') ?>

    <?php // echo $form->field($model, 'h_tid_yellow_cart') ?>

    <?php // echo $form->field($model, 'a_tid_yellow_cart') ?>

    <?php // echo $form->field($model, 'h_tid_red_cart') ?>

    <?php // echo $form->field($model, 'a_tid_red_cart') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
