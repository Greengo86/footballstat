<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Footballstat';
//echo debug($goal);
//debug ($content);
//echo debug($team_corner);
//echo debug($team);
//echo debug($team_max);
//echo debug($team_embl);
?>

<section id="services" class="section section-padded">
    <div class="container">
        <div class="row text-center title">
            <h2 class="black">Статистические факты</h2>
            <h4 class="light muted">Самое интересное из Ведущих Европейских чемпионатов</h4>
            <h2 class="light muted margin-champ"><?php echo $champ?> <?php echo Html::img('@web/img/embl/lfp.png', ['alt' => 'spain']) ?></h2>
        </div>
        <div id="carousel-example-generic" class="carousel slide owl-carousel" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/heart-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Самая забивающая команда</h4>
                                <p class="description"><?php echo $team_goal?> <?php echo Html::img($team_embl_goal)?> : <br> <?php echo $team_max_goal?> гола за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/corner_resize_red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех подали угловых ударов</h4>
                                <p class="description"><?php echo $team_corner?> <?php echo Html::img($team_embl_corner)?> : <br> <?php echo $team_max_corner?> угловых за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/foul.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех фолили на сопернике</h4>
                                <p class="description"><?php echo $team_foul?> <?php echo Html::img($team_embl_foul)?> : <br> <?php echo $team_max_foul?> фола за игру</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/owngoal.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Самая пропускающая команда</h4>
                                <p class="description"><?php echo $team_own_goal?> <?php echo Html::img($team_embl_owngoal)?> : <br> <?php echo $team_max_owngoal?> гола за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/guru-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех владеют мячом</h4>
                                <p class="description"><?php echo $team_posses?> <?php echo Html::img($team_embl_posses)?> : <br> <?php echo $team_max_posses?> % за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/weight-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Чаще всех получают жёлтые карточки</h4>
                                <p class="description"><?php echo $team_yelCart?> <?php echo Html::img($team_embl_yelCart)?> : <br> <?php echo $team_max_yelCart?> карточки за игру</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row text-center title">
            <h2 class="light muted margin-champ">Германия <?php echo Html::img('@web/img/embl/bundes.png', ['alt' => 'Bundesliga'])?></h2>
        </div>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner owl-carousel">
                <div class="item active">
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/heart-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Самая забивающая команда</h4>
                                <p class="description"><?php echo $team_goal?> <?php echo Html::img($team_embl_goal)?> : <br> <?php echo $team_max_goal?> гола за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/corner_resize_red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех подали угловых ударов</h4>
                                <p class="description"><?php echo $team_corner?> <?php echo Html::img($team_embl_corner)?> : <br> <?php echo $team_max_corner?> угловых за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/foul.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех фолили на сопернике</h4>
                                <p class="description"><?php echo $team_foul?> <?php echo Html::img($team_embl_foul)?> : <br> <?php echo $team_max_foul?> фола за игру</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/owngoal.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Самая пропускающая команда</h4>
                                <p class="description"><?php echo $team_own_goal?> <?php echo Html::img($team_embl_owngoal)?> : <br> <?php echo $team_max_owngoal?> гола за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/guru-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех владеют мячом</h4>
                                <p class="description"><?php echo $team_posses?> <?php echo Html::img($team_embl_posses)?> : <br> <?php echo $team_max_posses?> % за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/weight-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Чаще всех получают жёлтые карточки</h4>
                                <p class="description"><?php echo $team_yelCart?> <?php echo Html::img($team_embl_yelCart)?> : <br> <?php echo $team_max_yelCart?> карточки за игру</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row text-center title">
            <h2 class="light muted margin-champ">Англия <?php echo Html::img('@web/img/embl/pl.png', ['alt' => 'Premier League'])?></h2>
        </div>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner owl-carousel">
                <div class="item active">
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/heart-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Самая забивающая команда</h4>
                                <p class="description"><?php echo $team_goal?> <?php echo Html::img($team_embl_goal)?> : <br> <?php echo $team_max_goal?> гола за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/corner_resize_red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех подали угловых ударов</h4>
                                <p class="description"><?php echo $team_corner?> <?php echo Html::img($team_embl_corner)?> : <br> <?php echo $team_max_corner?> угловых за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/foul.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех фолили на сопернике</h4>
                                <p class="description"><?php echo $team_foul?> <?php echo Html::img($team_embl_foul)?> : <br> <?php echo $team_max_foul?> фола за игру</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/owngoal.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Самая пропускающая команда</h4>
                                <p class="description"><?php echo $team_own_goal?> <?php echo Html::img($team_embl_owngoal)?> : <br> <?php echo $team_max_owngoal?> гола за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/guru-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Больше всех владеют мячом</h4>
                                <p class="description"><?php echo $team_posses?> <?php echo Html::img($team_embl_posses)?> : <br> <?php echo $team_max_posses?> % за игру</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service">
                                <div class="icon-holder">
                                    <img src="/img/icons/weight-red.png" alt="" class="icon">
                                </div>
                                <h4 class="heading">Чаще всех получают жёлтые карточки</h4>
                                <p class="description"><?php echo $team_yelCart?> <?php echo Html::img($team_embl_yelCart)?> : <br> <?php echo $team_max_yelCart?> карточки за игру</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row text-center title">
    <h2 class="light muted margin-champ"><?php echo $champ?> <?php echo Html::img('@web/img/embl/lfp.png', ['alt' => 'spain']) ?></h2>
</div>
<div class="container">
    <div class="owl-carousel owl-theme">
        <div class="col-md-5">
            <h4 class="heading text-justify">Самая забивающая команда</h4>
            <p class="description img-responsive"><?php echo $team_goal?> <?php echo Html::img($team_embl_goal)?> <br><?php echo $team_max_goal?> гола за игру</p>
        </div>
        <div class="col-md-5">
            <h4 class="heading text-justify">Самая пропускающая команда</h4>
            <p class="description img-responsive"><?php echo $team_own_goal?> <?php echo Html::img($team_embl_owngoal)?><br><?php echo $team_max_owngoal?> гола за игру</p>
        </div>
        <div class="col-md-5">
            <h4 class="heading text-justify">Больше всех подали угловых ударов</h4>
            <p class="description img-responsive"><?php echo $team_corner?> <?php echo Html::img($team_embl_corner)?><br><?php echo $team_max_corner?> угловых за игру</p>
        </div>
        <div class="col-md-5">
            <h4 class="heading text-justify">Больше всех фолили на сопернике</h4>
            <p class="description img-responsive"><?php echo $team_foul?> <?php echo Html::img($team_embl_foul)?><br><?php echo $team_max_foul?> фола за игру</p>
        </div>
        <div class="col-md-5">
            <h4 class="heading">Больше всех владеют мячом</h4>
            <p class="description"><?php echo $team_posses?> <?php echo Html::img($team_embl_posses)?><br><?php echo $team_max_posses?> % за игру</p>
        </div>
        <div class="col-md-5">
            <h4 class="heading">Чаще всех получают жёлтые карточки</h4>
            <p class="description"><?php echo $team_yelCart?> <?php echo Html::img($team_embl_yelCart)?><br><?php echo $team_max_yelCart?> карточки за игру</p>
        </div>
    </div>
</div>