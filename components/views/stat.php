<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Footballstat';
//echo debug($goal);
//debug ($champ);
//echo debug($team_corner);
//echo debug($team);

?>

<div class="row text-center title">
    <h2 class="light muted margin-champ"><?php echo $league['league'] ?><?php echo Html::img('@web/img/champ/' . $champ . '.png'); ?></h2>
</div>
<div class="owl-carousel owl-theme">
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Самая забивающая команда</h4>
        </div>
        <p class="description img-responsive"><?php echo $team_goal ?> <?php echo Html::img($team_embl_goal) ?><?php echo $team_max_goal ?>
            гола за игру</p>
    </div>
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Самая пропускающая команда</h4>
        </div>
        <p class="description img-responsive"><?php echo $team_own_goal ?> <?php echo Html::img($team_embl_owngoal) ?><?php echo $team_max_owngoal ?>
            гола за игру</p>
    </div>
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Больше всех подали угловых ударов</h4>
        </div>
        <p class="description img-responsive"><?php echo $team_corner ?> <?php echo Html::img($team_embl_corner) ?><?php echo $team_max_corner ?>
            угловых за игру</p>
    </div>
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Больше всех фолили на сопернике</h4>
        </div>
        <p class="description img-responsive"><?php echo $team_foul ?> <?php echo Html::img($team_embl_foul) ?><?php echo $team_max_foul ?>
            фола за игру</p>
    </div>
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Больше всех владеют мячом</h4>
        </div>
        <p class="description"><?php echo $team_posses ?> <?php echo Html::img($team_embl_posses) ?><?php echo $team_max_posses ?>
            % за игру</p>
    </div>
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Чаще всех попадают в оффсайд</h4>
        </div>
        <p class="description"><?php echo $team_offside ?> <?php echo Html::img($team_embl_offside) ?><?php echo $team_max_offside ?>
            оффсайда за игру</p>
    </div>
    <div class="col-md-6">
        <div class="block-head">
            <h4 class="heading">Чаще всех получают жёлтые карточки</h4>
        </div>
        <p class="description"><?php echo $team_yelCart ?> <?php echo Html::img($team_embl_yelCart) ?><?php echo $team_max_yelCart ?>
            карточки за игру</p>
    </div>
</div>


