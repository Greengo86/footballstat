<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Team */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="team-form container">

    <p>
        <?= Html::a('Создать Игру', ['play/create'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Список Команд', ['team/index'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Список игр', ['play/index'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'team_league')->dropDownList(['1' => 'Испания', '2' => 'Англия', '3' => 'Германия', '4' => 'Италия']) ?>

    <?= $form->field($model, 'team_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_embl')->fileInput(); ?>

    <?= $form->field($model, 'team_stadium')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-danger' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

