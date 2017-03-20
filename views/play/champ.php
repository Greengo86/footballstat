<?php

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Статистика матчей:  ' . $play[$id]['league']['league'];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php //debug($id);?>

<div class="container">
    <h1 class=""><p class="text-center text-info"><?= Html::encode($this->title) ?></p></h1>

    <?php if(empty($play)){
        echo 'Матчей указанного чемпионата не найдено';
    }
    ?>

    <!--    Вывод выбора тура в чемпионате-->
    <div class="panel panel-danger">
        <div class="panel-heading"><h4>Выберите тур:</h4></div>
        <div class="panel-body">
            <div class="menu-tour">
                <ul>
                    <?php $y = ceil($playCount/10); $i = 1; while ($i <= $y): ?>
                        <li><a href="<?php echo Url::toRoute(['play/tour', 'id' => $id, 't' => $i])?>"><?php echo $i?></a></li>
                        <?php $i++?>
                    <?php endwhile ?>
                </ul>
            </div>
        </div>
    </div>

    <?php foreach ($play as $k => $game):?>
        <?php //debug($game);  ?>

        <!--Вывод ленты последних 20 матчей чемпионата-->
        <div class="col-md-12"> <p class="text-center date"><?= Yii::$app->formatter->asDatetime($game['date']);?></p></div>
        <div class="col-md-5 team-embl">
            <p class="text-right"><span class="team"><?php echo Html::img($game['teamHome']['team_embl']); echo $game['teamHome']['team_name']?> </span></p>
        </div>
        <div class="col-md-2 score">
            <p class="text-center">
                <?php if($game['delay'] == 1){
                    echo 'Матч перенеcён';
                }else{ echo $game['home_score_full']?>
                :
                <?php echo $game['away_score_full'];}?>
            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span class="team"><?php echo $game['teamAway']['team_name']?><?php echo Html::img($game['teamAway']['team_embl']); ?></span></p>
        </div>
        <br class="">
        <div class="col-md-12 text-center">
            <a class="show-match" href="<?php echo Url::to(['play/match', 'id' => $play[$k]['id']]) ?>" data-id="<?= $play[$k]['id']?>"><i class="glyphicon glyphicon-stats"></i>Статистика матча</a>
                <br>
            <hr class="style1">
        </div>
    <?php endforeach ?>
</div>
