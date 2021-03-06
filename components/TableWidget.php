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

        $dateToSearch = getDateTo_Search();

        $array['home'] = Play::find()->asArray()->with('teamHome', 'teamAway', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();

        foreach ($array['home'] as $value){
            if($value['teamHome']['is_active'] == 1){
                $this->play['home'][] = $value;
                $this->champ = $value['league'];
            }
        }

        $this->play['away'] = Play::find()->asArray()->with('teamAway', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $this->play['count'] = Play::find()->orderBy('id')->where(['league_id' => $this->champ, 'delay' => 0])->count();

//        var_dump($this->play['count']);die();

        /** Записываем в переменную $this->champ используя цикл foreach строку с названием выводимой во view Лиги */
        foreach ($this->play['home'] as $game) {
            $this->champ = $game['league']['league'];
            break;
        }

//        Получаем список статистических показателей дома и на выезде - Голы в матче
//        var_dump($this->play['away']);
//        die();
        $goalHome = team::statHome(isset($this->play['home']) ? $this->play['home'] : null, self::HOME_GOAL, 'goalHome');
        $goalAway = team::statAway($this->play['away'], self::AWAY_GOAL, 'goalAway');

//        Пропушённые голы
        $goalOwnHome = team::statHome($this->play['home'], self::AWAY_GOAL, 'goalAway');
        $goalOwnAway = team::statAway($this->play['away'], self::HOME_GOAL, 'goalHome');

//        var_dump($goalAway);die();
//        Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
        $score['goal'] = team::statSum($goalHome, $goalAway);
        $score['ownGoal'] = team::statOwnGoalSum($goalOwnHome, $goalOwnAway);

//        Получаем отсортированный массив в виде Команда => значение: игры => X, очки => X, голы => X, пропголы => X
        $this->score['table'] = team::getTable($this->play['home'], $score['goal'], $score['ownGoal']);

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