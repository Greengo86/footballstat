<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Play */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="play-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'league_id')->textInput() ?>

    <?= $form->field($model, 'home_team_id')->textInput() ?>

    <?= $form->field($model, 'away_team_id')->textInput() ?>

    <?= $form->field($model, 'home_score_full')->textInput() ?>

    <?= $form->field($model, 'away_score_full')->textInput() ?>

    <?= $form->field($model, 'h_tid_posses')->textInput() ?>

    <?= $form->field($model, 'a_tid_posses')->textInput() ?>

    <?= $form->field($model, 'h_tid_shot_on_goal')->textInput() ?>

    <?= $form->field($model, 'a_tid_shot_on_goal')->textInput() ?>

    <?= $form->field($model, 'h_tid_foul')->textInput() ?>

    <?= $form->field($model, 'a_tid_foul')->textInput() ?>

    <?= $form->field($model, 'h_tid_corner')->textInput() ?>

    <?= $form->field($model, 'a_tid_corner')->textInput() ?>

    <?= $form->field($model, 'h_tid_offside')->textInput() ?>

    <?= $form->field($model, 'a_tid_offside')->textInput() ?>

    <?= $form->field($model, 'h_tid_yellow_cart')->textInput() ?>

    <?= $form->field($model, 'a_tid_yellow_cart')->textInput() ?>

    <?= $form->field($model, 'h_tid_red_cart')->textInput() ?>

    <?= $form->field($model, 'a_tid_red_cart')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
