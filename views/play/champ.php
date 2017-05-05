<?php

use yii\helpers\Html;
use yii\helpers\Url;

//Получаем название лиги из массива $play['home'] в первой же игре и для любого чемпионата
foreach ($play as $land) {
    $league = $land['league']['league'];
    break;
}

$this->title = 'Статистика матчей:  ' . $league;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <h1><p class="text-center text-info"><?= Html::encode($this->title) ?></p></h1>

    <?php if (empty($play)) {
        echo 'Матчей указанного чемпионата не найдено';
    }
    ?>

<!--    Выводим меню выбора тура, если $main_page не передана! Значит мы на странице /play/champ/$id!-->
<!--    Если передана и сущесвует, то мы на главной странице и не выводим панельку выбора тура-->
    <?php if (!isset($main_page)): ?>
    <!--    Вывод выбора тура в чемпионате-->
    <div class="panel panel-danger">
        <div class="panel-heading"><h4>Выберите тур:</h4></div>
        <div class="panel-body">
            <div class="menu-tour">
                <ul>
<!--                    Переменную $limit принимаем из контроллера в зависимости от чемпионата. Если это Германия($id=3),-->
<!--                    то $limit 9 матчей - по кол-ву игр в туре, в остальных случаях 10. Далее делим на кол-во сыгранных матчей-->
<!--                    в чемпионате, округляем в большую сторону(ceil) и получаем кол-во туров в виде ссылок, кликнув по которым можно пеерейти на необходимый тур-->
                    <?php $y = ceil($playCount / $limit);
                    $i = 1;
                    while ($i <= $y): ?>
                        <li>
                            <a href="<?php echo Url::toRoute(['play/tour', 'id' => $id, 't' => $i]) ?>"><?php echo $i ?></a>
                        </li>
                        <?php $i++ ?>
                    <?php endwhile ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php foreach ($play as $k => $game): ?>

        <!--Вывод ленты последних 20 матчей чемпионата-->
        <div class="col-md-12"><p class="text-center text-info"><?= Yii::$app->formatter->asDatetime($game['date']); ?></p>
        </div>
        <div class="col-md-5 team-embl">
            <p class="text-right"><span class="team"><?php echo Html::img($game['teamHome']['team_embl']);
                    echo $game['teamHome']['team_name'] ?> </span></p>
        </div>
        <div class="col-md-2 score">
            <p class="text-center">
                <?php if ($game['delay'] == 1) {
                    echo 'Матч перенеcён';
                } else {
                    echo $game['home_score_full'] ?>
                    :
                    <?php echo $game['away_score_full'];
                } ?>
            </p>
        </div>
        <div class="col-md-5">
            <p class="text-left"><span
                        class="team"><?php echo $game['teamAway']['team_name'] ?><?php echo Html::img($game['teamAway']['team_embl']); ?></span>
            </p>
        </div>
        <br class="">
        <div class="col-md-12 text-center">
            <a class="show-match" href="<?php echo Url::to(['play/match', 'id' => $play[$k]['id']]) ?>"
               data-id="<?= $play[$k]['id'] ?>"><i class="glyphicon glyphicon-stats"></i>Статистика</a>
            <br>
            <hr class="style1">
        </div>
    <?php endforeach ?>
</div>

<script>
    /**
     * функция для вывода подробной статистика матча класса show-match в модальном окне
     */
    $('.show-match').on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            url: '/play/match',
            data: {id: id},
            type: 'GET',
            success: function(res){
                // console.log(res);
                showStat(res);
            },
            error: function(){
                alert('Ошибка выдачи подробной статистики матча');
            }
        });
    });

    function showStat(match){
        $('#match .modal-body').html(match);
        $('#match').modal();
    }
</script>