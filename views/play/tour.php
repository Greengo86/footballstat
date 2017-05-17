<?php

use yii\helpers\Html;
use yii\helpers\Url;


foreach ($tour as $k => $game):
    $this->title = 'Статистика матчей:  ' . $tour[$k]['league']['league'] . ' ' . $t . ' тур';
endforeach;
?>

<?php //debug($tour);?>

<div class="container">
    <p class="">
    <h1 class="text-center text-info"><?= Html::encode($this->title) ?></h1></p>

    <?php if (empty($tour)) {
        echo 'Матчей указанного тура не найдено';
    }
    ?>

    <!--    Вывод выбора тура в чемпионате-->
    <div class="panel panel-danger">
        <div class="panel-heading"><h4>Выберите тур:</h4></div>
        <div class="panel-body">
            <div class="menu-tour">
                <ul>
                    <?php $y = ceil($playCount / 10);
                    $i = 1;
                    while ($i <= $y): ?> <!-- Округляем количество туров в большую сторону-->
                        <li>
                            <a href="<?php echo Url::toRoute(['play/tour', 'id' => $id, 't' => $i]) ?>"><?php echo $i ?></a>
                        </li>
                        <?php $i++ ?>
                    <?php endwhile ?>
                </ul>
            </div>
        </div>
    </div>

    <?php //debug($playCount);  ?>

    <?php foreach ($tour as $k => $game): ?>


        <div class="col-md-12"><p class="text-center date"><?= Yii::$app->formatter->asDatetime($game['date']); ?></p>
        </div>
        <!-- формат вывода даты-  , 'php: d.m.Y H:i'-->
        <?php if ($game['delay'] == 1): ?>
            <div class="col-md-5 team-embl">
                <p class="text-right"><span class="team"><?php echo Html::img($game['teamHome']['team_embl']);
                        echo $game['teamHome']['team_name'] ?> </span></p>
            </div>
            <div class="col-md-2 score">
                <p class="text-center"><?php echo 'Матч перенеcён' ?></p>
            </div>
            <div class="col-md-5">
                <p class="text-left"><span
                            class="team"><?php echo $game['teamAway']['team_name'] ?><?php echo Html::img($game['teamAway']['team_embl']); ?></span>
                </p>
            </div>
            <br class="">
            <div class="clearfix"></div>
            <div class="text-center">
                <a class="show-match" href="<?php echo Url::to(['play/match', 'id' => $tour[$k]['id']]) ?>"
                   data-id="<?= $tour[$k]['id'] ?>"><i class="glyphicon glyphicon-stats"></i>Статистика матча</a>
                <br>
            </div>
            <hr class="style1">

        <?php elseif ($game['delay'] == 0): ?>
            <div class="col-md-5 team-embl">
                <p class="text-right"><span class="team"><?php echo Html::img($game['teamHome']['team_embl']);
                        echo $game['teamHome']['team_name'] ?> </span></p>
            </div>
            <div class="col-md-2 score">
                <p class="text-center"><?php echo $game['home_score_full'] ?>:<?php echo $game['away_score_full'] ?></p>
            </div>
            <div class="col-md-5">
                <p class="text-left"><span
                            class="team"><?php echo $game['teamAway']['team_name'] ?><?php echo Html::img($game['teamAway']['team_embl']); ?></span>
                </p>
            </div>
            <br class="">
            <div class="text-center">
                <a class="show-match" href="<?php echo Url::to(['play/match', 'id' => $tour[$k]['id']]) ?>"
                   data-id="<?= $tour[$k]['id'] ?>"><i class="glyphicon glyphicon-stats"></i>Статистика матча</a>
                <br>
            </div>
            <hr class="style1">
        <?php endif; ?>
    <?php endforeach ?>
</div>
