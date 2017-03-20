<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.10.16
 * Time: 21:39
 */

namespace app\components;


use yii\helpers\Html;
use Yii;
use yii\base\Widget;
use app\models\Play;
use app\models\Team;
use yii\caching\DbDependency;
use yii\db\ActiveRecord;

class StatWidget extends Widget
{

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


    public $statHome;
    public $statAway;
    public $score;
    public $champ;
    public $team;
    public $play;
    public $team_max;
    public $team_embl;


    public  function init()
    {

        parent::init();

        $this->play['home'] = Play::find()->asArray()->with('teamHome', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $this->play['away'] = Play::find()->asArray()->with('teamAway', 'league')->where(['league_id' => $this->champ, 'delay' => 0])->indexBy('id')->all();
        $this->play['count'] = Play::find()->orderBy('id')->where(['league_id' => $this->champ, 'delay' => 0])->count();

//        $playHome = Play::find()->asArray()->with('teamHome', 'league')->indexBy('id')->all();
//        $playAway = Play::find()->asArray()->with('teamAway', 'league')->indexBy('id')->all();
//        $playCount = Play::find()->orderBy('id')->count();

        $model = new Team();

//        Получаем список статистических показателей дома и на выезде - Голы в матче
        $goalHome = $model->statGoalHome($this->play['home'], self::HOME_GOAL);
        $goalAway = $model->statGoalAway($this->play['away'], self::AWAY_GOAL);

//        Пропушённые голы
        $goalOwnHome = $model->statGoalOwnHome($this->play['home'], self::AWAY_GOAL);
        $goalOwnAway = $model->statGoalOwnAway($this->play['away'], self::HOME_GOAL);

//        Угловые
        $cornerHome = $model->statCornerHome($this->play['home'], self::HOME_CORNER);
        $cornerAway = $model->statCornerAway($this->play['away'], self::AWAY_CORNER);

//        Фолы
        $foulHome = $model->statFoulHome($this->play['home'], self::HOME_FOUL);
        $foulAway = $model->statFoulAway($this->play['away'], self::AWAY_FOUL);

//        Процент владения мячом
        $possesHome = $model->statPossesHome($this->play['home'], self::HOME_POSSESSION);
        $possesAway = $model->statPossesAway($this->play['away'], self::AWAY_POSSESSION);

//       Оффсайды
        $offsideHome = $model->statOffsideHome($this->play['home'], self::HOME_OFFSIDE);
        $offsideAway = $model->statOffsideAway($this->play['away'], self::AWAY_OFFSIDE);

//        Жёлтые карточки
        $yelCartHome = $model->statYelCartHome($this->play['home'], self::HOME_YELLOW_CART);
        $yelCartAway = $model->statYelCartAway($this->play['away'], self::AWAY_YELLOW_CART);

//        суммируем значения дома и на выезде для каждой команды
//        Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
        $this->score['goal'] = $model->statSum($goalHome, $goalAway, $this->play['count']);
        $this->score['corner'] = $model->statSum($cornerHome, $cornerAway, $this->play['count']);
        $this->score['foul'] = $model->statSum($foulHome, $foulAway, $this->play['count']);
        $this->score['posses'] = $model->statSum($possesHome, $possesAway, $this->play['count']);
        $this->score['offside'] = $model->statSum($offsideHome, $offsideAway, $this->play['count']);
        $this->score['yelCart'] = $model->statSum($yelCartHome, $yelCartAway, $this->play['count']);
        $this->score['ownGoal'] = $model->statOwnGoalSum($goalOwnHome, $goalOwnAway, $this->play['count']);

        //Определяем команду, с наибольшим коэфициентом
        $this->team['goal'] = $model->statMaxTeam($this->score['goal']);
        //Вычисляем максимальное значение в массиве
        $this->team_max['goal'] = $model->statMax($this->score['goal']);
        $this->team_embl['goal'] = $model->teamEmbl($this->play['home'], $this->team['goal']);

        $this->team['ownGoal'] = $model->statMaxTeam($this->score['ownGoal']);
        $this->team_max['ownGoal'] = $model->statMax($this->score['ownGoal']);
        $this->team_embl['ownGoal'] = $model->teamEmbl($this->play['home'], $this->team['ownGoal']);

        $this->team['corner'] = $model->statMaxTeam($this->score['corner']);
        $this->team_max['corner'] = $model->statMax($this->score['corner']);
        $this->team_embl['corner'] = $model->teamEmbl($this->play['home'], $this->team['corner']);

        $this->team['foul'] = $model->statMaxTeam($this->score['foul']);
        $this->team_max['foul'] = $model->statMax($this->score['foul']);
        $this->team_embl['foul'] = $model->teamEmbl($this->play['home'], $this->team['foul']);

        $this->team['posses'] = $model->statMaxTeam($this->score['posses']);
        $this->team_max['posses'] = $model->statMax($this->score['posses']);
        $this->team_embl['posses'] = $model->teamEmbl($this->play['home'], $this->team['posses']);

        $this->team['offside'] = $model->statMaxTeam($this->score['offside']);
        $this->team_max['offside'] = $model->statMax($this->score['offside']);
        $this->team_embl['offside'] = $model->teamEmbl($this->play['home'], $this->team['offside']);

        $this->team['yelCart'] = $model->statMaxTeam($this->score['yelCart']);
        $this->team_max['yelCart'] = $model->statMax($this->score['yelCart']);
        $this->team_embl['yelCart'] = $model->teamEmbl($this->play['home'], $this->team['yelCart']);

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
            //Из вида site/index передаём название чемпионата
            'champ' => $this->play['home'][1]['league']['league'],
            'score' => $this->score['ownGoal'],

        ]);
    }

}