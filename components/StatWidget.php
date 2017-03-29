<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.10.16
 * Time: 21:39
 */

namespace app\components;


use Yii;
use yii\base\Widget;
use app\models\Play;
use app\models\Team;
use yii\db\ActiveRecord;

class StatWidget extends Widget
{

    /* Константы, определённые для более простого определения того статистического показателя, который нужно запросить */
    const HOME_GOAL = 'home_score_full';
    const AWAY_GOAL = 'away_score_full';
    const HOME_POSSESSION = 'h_tid_posses';
    const AWAY_POSSESSION = 'a_tid_posses';
    const HOME_SHOT = 'h_tid_shot_on_goal';
    const AWAY_SHOT = 'a_tid_shot_on_goal';
    const HOME_FOUL = 'h_tid_foul';
    const AWAY_FOUL = 'a_tid_foul';
    const HOME_CORNER = 'h_tid_corner';
    const AWAY_CORNER = 'a_tid_corner';
    const HOME_OFFSIDE = 'h_tid_offside';
    const AWAY_OFFSIDE = 'a_tid_offside';
    const HOME_YELLOW_CART = 'h_tid_yellow_cart';
    const AWAY_YELLOW_CART = 'a_tid_yellow_cart';
    const HOME_RED_CART = 'h_tid_red_cart';
    const AWAY_RED_CART = 'a_tid_red_cart';


    public $score;
    public $champ;
    public $league;
    public $team;
    public $team_max;
    public $team_embl;


    public function init()
    {

        parent::init();

        $play['home'] = Play::find()->asArray()->with('teamHome', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $play['away'] = Play::find()->asArray()->with('teamAway', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $play['count'] = Play::find()->orderBy('id')->where(['league_id' => $this->champ, 'delay' => 0])->count();

        $model = new Team();

//        Получаем список статистических показателей дома и на выезде - Голы в матче
        $goalHome = team::statGoalHome($play['home'], self::HOME_GOAL);
        $goalAway = team::statGoalAway($play['away'], self::AWAY_GOAL);

//        Пропушённые голы
        $goalOwnHome = team::statGoalOwnHome($play['home'], self::AWAY_GOAL);
        $goalOwnAway = team::statGoalOwnAway($play['away'], self::HOME_GOAL);

//        Угловые
        $cornerHome = team::statCornerHome($play['home'], self::HOME_CORNER);
        $cornerAway = team::statCornerAway($play['away'], self::AWAY_CORNER);

//        Фолы
        $foulHome = team::statFoulHome($play['home'], self::HOME_FOUL);
        $foulAway = team::statFoulAway($play['away'], self::AWAY_FOUL);

//        Процент владения мячом
        $possesHome = team::statPossesHome($play['home'], self::HOME_POSSESSION);
        $possesAway = team::statPossesAway($play['away'], self::AWAY_POSSESSION);

//       Оффсайды
        $offsideHome = team::statOffsideHome($play['home'], self::HOME_OFFSIDE);
        $offsideAway = team::statOffsideAway($play['away'], self::AWAY_OFFSIDE);

//        Жёлтые карточки
        $yelCartHome = team::statYelCartHome($play['home'], self::HOME_YELLOW_CART);
        $yelCartAway = team::statYelCartAway($play['away'], self::AWAY_YELLOW_CART);

//        суммируем значения дома и на выезде для каждой команды
//        Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
        $this->score['goal'] = team::statSum($goalHome, $goalAway, $play['count']);
        $this->score['corner'] = team::statSum($cornerHome, $cornerAway, $play['count']);
        $this->score['foul'] = team::statSum($foulHome, $foulAway, $play['count']);
        $this->score['posses'] = team::statSum($possesHome, $possesAway, $play['count']);
        $this->score['offside'] = team::statSum($offsideHome, $offsideAway, $play['count']);
        $this->score['yelCart'] = team::statSum($yelCartHome, $yelCartAway, $play['count']);
        $this->score['ownGoal'] = team::statOwnGoalSum($goalOwnHome, $goalOwnAway, $play['count']);

        //Определяем команду, с наибольшим коэфициентом
        $this->team['goal'] = team::statMaxTeam($this->score['goal']);
        //Вычисляем максимальное значение в массиве
        $this->team_max['goal'] = team::statMax($this->score['goal']);
        $this->team_embl['goal'] = team::teamEmbl($play['home'], $this->team['goal']);

        $this->team['ownGoal'] = team::statMaxTeam($this->score['ownGoal']);
        $this->team_max['ownGoal'] = team::statMax($this->score['ownGoal']);
        $this->team_embl['ownGoal'] = team::teamEmbl($play['home'], $this->team['ownGoal']);

        $this->team['corner'] = team::statMaxTeam($this->score['corner']);
        $this->team_max['corner'] = team::statMax($this->score['corner']);
        $this->team_embl['corner'] = team::teamEmbl($play['home'], $this->team['corner']);

        $this->team['foul'] = team::statMaxTeam($this->score['foul']);
        $this->team_max['foul'] = team::statMax($this->score['foul']);
        $this->team_embl['foul'] = team::teamEmbl($play['home'], $this->team['foul']);

        $this->team['posses'] = team::statMaxTeam($this->score['posses']);
        $this->team_max['posses'] = team::statMax($this->score['posses']);
        $this->team_embl['posses'] = team::teamEmbl($play['home'], $this->team['posses']);

        $this->team['offside'] = team::statMaxTeam($this->score['offside']);
        $this->team_max['offside'] = team::statMax($this->score['offside']);
        $this->team_embl['offside'] = team::teamEmbl($play['home'], $this->team['offside']);

        $this->team['yelCart'] = team::statMaxTeam($this->score['yelCart']);
        $this->team_max['yelCart'] = team::statMax($this->score['yelCart']);
        $this->team_embl['yelCart'] = team::teamEmbl($play['home'], $this->team['yelCart']);

        //Получаем название лиги из массива $play['home'] в первой же игре
        foreach ($play['home'] as $land) {
            $this->league['league'] = $land['league']['league'];
            break;
        }

    }

    public function run()
    {
        return $this->render('stat', [
            'team_goal' => $this->team['goal'],
            'team_max_goal' => $this->team_max['goal'],
            'team_embl_goal' => $this->team_embl['goal'],
            'team_own_goal' => $this->team['ownGoal'],
            'team_max_owngoal' => $this->team_max['ownGoal'],
            'team_embl_owngoal' => $this->team_embl['ownGoal'],
            'team_corner' => $this->team['corner'],
            'team_max_corner' => $this->team_max['corner'],
            'team_embl_corner' => $this->team_embl['corner'],
            'team_foul' => $this->team['foul'],
            'team_max_foul' => $this->team_max['foul'],
            'team_embl_foul' => $this->team_embl['foul'],
            'team_posses' => $this->team['posses'],
            'team_max_posses' => $this->team_max['posses'],
            'team_embl_posses' => $this->team_embl['posses'],
            'team_offside' => $this->team['offside'],
            'team_max_offside' => $this->team_max['offside'],
            'team_embl_offside' => $this->team_embl['offside'],
            'team_yelCart' => $this->team['yelCart'],
            'team_max_yelCart' => $this->team_max['yelCart'],
            'team_embl_yelCart' => $this->team_embl['yelCart'],
            //Из вида site/index передаём название чемпионата, в виде числа [1, 2, 3]
            'champ' => $this->champ,
            //Передаём Лигу в текстовом виде
            'league' => $this->league,
        ]);
    }

}