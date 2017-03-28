<?php


namespace app\components;


use app\models\Team;
use app\models\Play;
use yii\base\Widget;

class TableWidget extends Widget
{

    const HOME_GOAL = 'home_score_full';
    const AWAY_GOAL = 'away_score_full';

    public $champ;
    public $score;
    public $play;

    public function init()
    {

        parent::init();

        $this->play['home'] = Play::find()->asArray()->with('teamHome', 'teamAway', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $this->play['away'] = Play::find()->asArray()->with('teamAway', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $this->play['count'] = Play::find()->orderBy('id')->where(['league_id' => $this->champ, 'delay' => 0])->count();

        /** Записываем в переменную $this->champ используя цикл foreach строку с названием выводимой во view Лиги */
        foreach ($this->play['home'] as $game) {
            $this->champ = $game['league']['league'];
            break;
        }

        $model = new Team();

//        Получаем список статистических показателей дома и на выезде - Голы в матче
        $goalHome = $model->statGoalHome($this->play['home'], self::HOME_GOAL);
        $goalAway = $model->statGoalAway($this->play['away'], self::AWAY_GOAL);

//        Пропушённые голы
        $goalOwnHome = $model->statGoalOwnHome($this->play['home'], self::AWAY_GOAL);
        $goalOwnAway = $model->statGoalOwnAway($this->play['away'], self::HOME_GOAL);

//        Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
        $this->score['goal'] = $model->statSum($goalHome, $goalAway);
        $this->score['ownGoal'] = $model->statOwnGoalSum($goalOwnHome, $goalOwnAway);

//        Получаем количество сыгранных матчей в виде Команда => количество очков
//        $this->score['games'] = $model->getGames($this->play['home']);

//        Получаем отсортированный массив в виде Команда => количество очков
//        $this->score['points'] = $model->getPoints($this->play['home']);

//        Получаем отсортированный массив в виде Команда => значение: игры => X, очки => X, голы => X, пропголы => X
        $this->score['table'] = $model->getTable($this->play['home'], $this->score['goal'], $this->score['ownGoal']);

    }

    public function run()
    {

        return $this->render('table', [
            'table' => $this->score['table'],
            //Из вида site/index передаём название чемпионата
            'champ' => $this->champ,


        ]);
    }


}