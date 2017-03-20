<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\widgets\TouchSpin;
use kartik\widgets\RangeInput;
use app\models\League;
use app\models\Team;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Play */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="play-form container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'year')->dropDownList(['2017' => '2017 год', '2016' => '2016 год', '2018' => '2018 год']) ?>
    <label class="control-label" for="play-date">Дата</label>
    <?= DateTimePicker::widget([
    'model' => $model,
    'attribute' => 'date',
    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
    'options' => ['placeholder' => 'Введите дату начала матча...'],
    'convertFormat' => false,
    'pluginOptions' => [
    'format' => 'yyyy-mm-dd HH:ii:00',
    'todayHighlight' => true
    ]
    ]);?>

    <?= $form->field($model, 'delay')
    ->checkbox([
    'label' => 'Матч Перенесён',
        'labelOptions' => [
            'style' => 'padding-top:15px;'
        ],
    'uncheck' => 0,
    'checked' => 1
    ]);
    ?>

    <?//= $form->field($model, 'league_id')->dropDownList(['1' => 'Испания', '2' => 'Англия', '3' => 'Германия']) ?>
    <?= $form->field($model, 'league_id')->dropDownList(ArrayHelper::map(League::find()->all(), 'id', 'league'),[
            'prompt' => 'Лига',
            'onchange' => '$.post( "'.Yii::$app->urlManager->createUrl('admin/play/list_team?id=').'"+$(this).val(), function( data ) {$( "select#team_id" ).html( data );});'
    ]) ?>

    <?= $form->field($model, 'home_team_id')->dropDownList(ArrayHelper::map(Team::find()->all(), 'team_id', 'team_name'), ['id' => 'team_id']) ?>

    <?= $form->field($model, 'away_team_id')->dropDownList(ArrayHelper::map(Team::find()->all(), 'team_id', 'team_name'), ['id' => 'team_id']) ?>

    <?= $form->field($model, 'home_score_full')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество голов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'away_score_full')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество голов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'h_tid_posses')->widget(RangeInput::className(), [
    'html5Options' => ['min'=>0, 'max'=> 100, 'step'=>1],
    'options' => ['placeholder' => '50', 'class' => 'text-center'],
    'addon' => [
    'prepend' => ['content'=>'<span class="text-danger">0%</span>'],
    'preCaption' => '<span class="input-group-addon"><span class="text-success">100%</span></span><span class="input-group-addon"><strong>Value:</strong></span>',
    'append' => ['content'=>'%']
    ]]); ?>

    <?= $form->field($model, 'a_tid_posses')->widget(RangeInput::className(), [
        'html5Options' => ['min'=>0, 'max'=> 100, 'step'=>1],
        'options' => ['placeholder' => '50', 'class' => 'text-center'],
        'addon' => [
            'prepend' => ['content'=>'<span class="text-danger">0%</span>'],
            'preCaption' => '<span class="input-group-addon"><span class="text-success">100%</span></span><span class="input-group-addon"><strong>Value:</strong></span>',
            'append' => ['content'=>'%']
        ]]); ?>

    <?= $form->field($model, 'h_tid_shot_on_goal')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество ударов в створ...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'a_tid_shot_on_goal')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество ударов в створ...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'h_tid_foul')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество ударов в створ...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'a_tid_foul')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество ударов в створ...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'h_tid_corner')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество угловых...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'a_tid_corner')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество угловых...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'h_tid_offside')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество оффсайдов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'a_tid_offside')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество оффсайдов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'h_tid_yellow_cart')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество оффсайдов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'a_tid_yellow_cart')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество оффсайдов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'h_tid_red_cart')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество оффсайдов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>

    <?= $form->field($model, 'a_tid_red_cart')->widget(TouchSpin::className(), [
        'options' => ['placeholder' => 'Введите количество оффсайдов...', 'class' => 'input-lg'],
        'pluginOptions' => [
            'buttonup_class' => 'btn btn-danger',
            'buttondown_class' => 'btn btn-danger',
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-danger' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
