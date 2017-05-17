<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Play */

$this->title = 'Изменение игры №: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plays', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="play-update container">

    <?php echo Html::button("<span class='glyphicon glyphicon-home' aria-hidden='true'></span>",
        ['class' => 'kv-action-btn',
            'onclick' => "window.location.href = '" . 'index' . "';",
            'data-toggle' => 'tooltip',
            'title' => 'На главную админки',
        ])
    ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
