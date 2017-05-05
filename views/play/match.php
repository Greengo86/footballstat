<?php

use yii\helpers\Html;


$this->title = $match['teamHome']['team_name'] . ' - ' . $match['teamAway']['team_name'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    <h3 class="text-center"><?= $match['league']['league']?></h3>

    <?php //debug($match['date']);?>
    <?php //debug($match) ;?>


    <?php if(empty($match)){
        echo 'Указанный Вами матч отсутствует';
    }
    ?>

    <h1 class="text-center">Чемпионат - <?php echo $match['league']['league']?></h1>

    <h3 class="text-center"><?= Yii::$app->formatter->asDatetime($match['date']);?></h3>

    <hr class="style3">
<!--    Если матч перенесён мы Выводим сообветствующую информацию-->
    <?php if($match['delay'] == 1):?>
        <div class="col-md-5 team-embl">
            <p class="text-right"><?php echo Html::img($match['teamHome']['team_embl'])?><span class="team"><?php echo $match['teamHome']['team_name']?></span>
            </p>
        </div>
        <div class="col-md-2 score">
            <p class="text-center"><?php echo 'Матч перенеcён'?></p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="team"><?php echo $match['teamAway']['team_name']?></span><?php echo Html::img($match['teamAway']['team_embl'])?></p>
        </div>
        <br class="">

    <?php elseif($match['delay'] == 0):?>
    <div class="col-md-5 team-embl">
        <p class="text-right"><?php echo Html::img($match['teamHome']['team_embl'])?><span class="team"><?php echo $match['teamHome']['team_name']?></span>

        </p>
    </div>
    <div class="col-md-2 score">
        <p class="text-center"><?php echo $match['home_score_full']?>:<?php echo $match['away_score_full']?></p>
    </div>
    <div class="col-md-5">
        <p class="text-left"><span class="team"><?php echo $match['teamAway']['team_name']?></span><?php echo Html::img($match['teamAway']['team_embl'])?></p>
    </div>
    <br class="">

        <div class="col-md-5 score-match">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_posses']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Владение мячом</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_posses']?></span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_shot_on_goal']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Удары в створ</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_shot_on_goal']?></span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_foul']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Фолы</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_foul']?></span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_corner']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Угловые</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_corner']?></span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_offside']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Оффсайды</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_offside']?></span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_yellow_cart']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Жёлтые карточки</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_yellow_cart']?></span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-right"><span class="numeral"><?php echo $match['h_tid_red_cart']?></span>

            </p>
        </div>
        <div class="col-md-2">
            <p class="text-center"><span class="statistic">Красные карточки</span>

            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="numeral"><?php echo $match['a_tid_red_cart'];?></span>
            </p>
        </div>
    <?php endif;?>
</div>