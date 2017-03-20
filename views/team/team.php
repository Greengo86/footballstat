<?php

//debug($teamhome);
//debug($teamaway);

use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $this->title = 'Статистика команды:  '  . $teamhome[0]['teamHome']['team_name']; ?>

    <div class="container container-contact">
        <div class="row decor-default">
            <div class="col-md-12">
                <div class="text-info team-title text-center"><?= ($this->title) . Html::img($teamhome[0]['teamHome']['team_embl']) ?></div>
                <div class="contact">
                    <div class="controls">
                        <?= Html::img('@web/img/stadium/' . $t . '.jpg', ['alt' => 'stadium', 'class' => 'cover'])?>
<!--                        <img src="http://lorempixel.com/900/320/nature/5/" alt="cover" class="cover">-->
                        <div class="cont">
<!--                            <div class="name">--><?//= ($this->title) . Html::img($teamhome[0]['teamHome']['team_embl']) ?><!--</div>-->
                            <div class="ui-mark">Домашний Стадион: <?= $teamhome[0]['teamHome']['team_stadium'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php if(!empty($teamhome)):?>
<!--        <p class="text-info team-title">--><?//= ($this->title) . Html::img($teamhome[0]['teamHome']['team_embl']) ?><!--</p>-->

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral">Общее</span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Средняя результативность</span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral">Дома</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral">На выезде</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['score_full_f'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Голы в матче</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['score_home_full']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['score_away_full']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['shot_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Удары в створ</span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['shot_home']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['shot_away']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['posses_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Владение (в %)</span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['h_posses']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['a_posses']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['foul_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Фолы</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['h_foul']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['a_foul']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['corner_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Угловые</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['h_corner']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['a_corner']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['offside_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Оффсайды</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['h_offside']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['a_offside']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['yellow_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Жёлтые карточки</span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['h_yellow']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['a_yellow']?></span>

            </p>
        </div>

        <div class="col-xs-2 col-md-2">
            <p class="text-center"><span class="numeral"><?= $stat['red_full'] ?></span>

            </p>
        </div>
        <div class="col-xs-6 col-md-6">
            <p class="text-center"><span class="statistic">Красные карточки</span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-right"><span class="numeral"><?= $stat['h_red']?></span>

            </p>
        </div>
        <div class="col-xs-2 col-md-2">
            <p class="text-left"><span class="numeral"><?= $stat['a_red']?></span>
            </p>
        </div>
<?php else: ?>
    <p class="text-center text-info"><?= 'Указанная Вами команда отсутствует' ?></p>

    <!--            <p style="background-image: url('/img/stadium/alaves.jpg')">Стадион-->
    <!--            </p>-->

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Средняя результативность</span>

        </p>
    </div>

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Голы в матче</span>

        </p>
    </div>
    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Удары в створ</span>

        </p>
    </div>
    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Владение (в %)</span>

        </p>
    </div>

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Фолы</span>

        </p>
    </div>

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Угловые</span>

        </p>
    </div>

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Оффсайды</span>

        </p>
    </div>

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Жёлтые карточки</span>

        </p>
    </div>

    <div class="col-xs-12 col-md-12">
        <p class="text-center"><span class="statistic">Красные карточки</span>

        </p>
    </div>
<?php endif; ?>
    </div>
    <div class="container">
        <div class="panel panel-danger">
            <div class="panel-heading clickable">
                <h3 class="panel-title">Последние матчи</h3>
                <span class="pull-right "><i class="glyphicon glyphicon-minus"></i></span>
            </div>
            <div class="panel-body">
                <h3 class="panel-home">Последние матчи Дома:</h3>
                    <?php foreach ($limitHome as $game): ?>
                        <a class="show-match"
                            href="<?php echo Url::to(['play/match', 'id' => $game['id']])  ?>"
                            data-id="<?= $game['id'];?>"
                            <span class="pull-left"><?php echo $game['date'] . '  &nbsp' . $game['teamHome']['team_name'] . '  -&nbsp' . $game['teamAway']['team_name']?></span>
                            <span class="pull-right"><?php echo $game['home_score_full'] . ':' . $game['away_score_full']?></span>
                        </a>
                        <br/>
                    <?php endforeach;?>
                <h3 class="panel-home">Последние матчи На Выезде:</h3>
                    <?php foreach ($limitAway as $game):?>
                        <a class="show-match"
                           href="<?php echo Url::to(['play/match', 'id' => $game['id']])  ?>"
                           data-id="<?= $game['id'];?>"
                        <span class="pull-left"><?php echo $game['date'] . '  &nbsp' . $game['teamHome']['team_name'] . '  -&nbsp' . $game['teamAway']['team_name']?></span>
                        <span class="pull-right"><?php echo $game['home_score_full'] . ':' . $game['away_score_full']?></span>
                        </a>
                        <br/>
                    <?php endforeach;?>
            </div>
        </div>
    </div>
<!--    --><?php //debug($result ?>
<!--    --><?php //debug($limitAway) ?>
