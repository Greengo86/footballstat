<?php

namespace app\models;

use kartik\helpers\Html;
use Yii;
use yii\db\ActiveRecord;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Url;

/**
 * This is the model class for table "team".
 *
 * @property integer $team_id
 * @property string $team_name
 * @property string $team_embl
 * @property string $team_stadium
 * @property integer $team_league
 */
class Team extends ActiveRecord
{

    public $statHome;
    public $statAway;
    public $goalHome;
    public $goalAway;
    public $goalOwnHome;
    public $goalOwnAway;
    public $possesHome;
    public $possesAway;
    public $cornerHome;
    public $cornerAway;
    public $offsideHome;
    public $offsideAway;
    public $foulHome;
    public $foulAway;
    public $yelCartHome;
    public $yelCartAway;
    public $games;
    public $score;
    public $ownGoal;
    public $points;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_name', 'team_stadium', 'team_league'], 'required'],
            [['team_league'], 'integer'],
            [['team_embl'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['team_name'], 'string', 'max' => 50],
            [['team_stadium'], 'string', 'max' => 30]
        ];
    }

    /**
     * @return array
     * Метод для формирования массива для вывода меню на главной странице.
     */

    public static function teamMenu(){

        $item=[];
        $teamSpain = self::find()->asArray()->where(['team_league' => '1'])->all();
        $teamEngland = self::find()->asArray()->where(['team_league' => '2'])->all();
        $teamGermany = self::find()->asArray()->where(['team_league' => '3'])->all();

        foreach($teamSpain as $team){
            $arraySpain[] = [
                'label' =>$team['team_name'] . '    ' . \yii\helpers\Html::img($team['team_embl']),
                'url'=>['team/team', 'id' => 1, 't' => $team['team_id']]
            ];
        }

        foreach($teamEngland as $team){
            $arrayEngland[] = [
                'label' =>$team['team_name'] . '    ' . \yii\helpers\Html::img($team['team_embl']),
                'url'=>['team/team', 'id' => 1, 't' => $team['team_id']]
            ];
        }

        foreach($teamGermany as $team){
            $arrayGermany[] = [
                'label' =>$team['team_name'] . '    ' . \yii\helpers\Html::img($team['team_embl']),
                'url'=>['team/team', 'id' => 1, 't' => $team['team_id']]
            ];
        }

        $item[] = ['label' => 'Главная', 'url' => ['/']];
        $item[] = ['label' => 'Испания',
                    'items' => [
                        ['label' => 'Команды',
                        'items' => $arraySpain],
                        ['label' => 'h2h', 'url' => ['/play/champ', 'id' => 1]],
                        ['label' => 'Бомбардиры и ассисенты', 'url' => ['/scorer/scorers', 'id' => 1]]]
                    ];
        $item[] = ['label' => 'Германия',
                    'items' => [
                        ['label' => 'Команды',
                        'items' => $arrayGermany],
                        ['label' => 'h2h', 'url' => ['/play/champ', 'id' => 3]],
                        ['label' => 'Бомбардиры и ассисенты', 'url' => ['/scorer/scorers', 'id' => 3]]]
                    ];
        $item[] = ['label' => 'Англия',
                    'items' => [
                        ['label' => 'Команды',
                        'items' => $arrayEngland],
                        ['label' => 'h2h', 'url' => ['/play/champ', 'id' => 2]],
                        ['label' => 'Бомбардиры и ассисенты', 'url' => ['/scorer/scorers', 'id' => 2]]]
                ];
        $item[] = Yii::$app->user->isGuest ?
                    ['label' => 'Вход', 'url' => ['/admin']] :
                    ['label' => 'Выход (' . Url::to(Yii::$app->user->identity['username']) . ' ' . FA::icon('user') . ' ' . ')',
                        'url' => ['/site/logout']];

        $item[] = Yii::$app->user->isGuest ?
            ['label' => '', 'url' => ['']] :
            ['label' => 'Админка',
                'url' => ['/admin']];

            return $item;
    }

    public function getPlayHome(){
        return $this->hasOne(Play::className(), ['home_team_id' => 'team_id']);
    }

    public function getLeague(){
        return $this->hasOne(League::className(), ['league' => 'team_league']);
    }

    public function getid()
    {
        return $this->hasOne(Play::className(), ['home_team_id' => 'team_id']);
    }

//    public function getPlayAway(){
//        return $this->hasMany(Play::className(), ['away_team_id' => 'team_id']);
//    }

    /**
     * Вернём среднее арифметическое для каждого статического показателя команды
     */
    /**
     * @return int
     */
    public function average($stat, $qty)
    {
           return round($stat / $qty, 2);
    }

    public function averageFull($statHome, $statAway, $qtyHome, $qtyAway = 0)
    {
        return round(($statHome + $statAway) / ($qtyHome + $qtyAway), 2);
    }

    public function statGoalHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            $this->goalHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->goalHome;

    }

    public function statGoalAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playAway as $game) {
            $this->goalAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->goalAway;

    }

    public function statGoalOwnHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            $this->goalOwnHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->goalOwnHome;

    }

    public function statGoalOwnAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playAway as $game) {
            $this->goalOwnAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->goalOwnAway;

    }

    public function statFoulHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            $this->foulHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->foulHome;

    }

    public function statFoulAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playAway as $game) {
            $this->foulAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->foulAway;

    }

    public function statCornerHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            $this->cornerHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->cornerHome;

    }

    public function statCornerAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            $this->cornerAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->cornerAway;
    }

    public function statPossesHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playHome as $game) {
            $this->possesHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->possesHome;
    }

    public function statPossesAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            $this->possesAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->possesAway;
    }

    public function statOffsideHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playHome as $game) {
            $this->offsideHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->offsideHome;
    }

    public function statOffsideAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            $this->offsideAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->offsideAway;
    }

    public function statYelCartHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playHome as $game) {
            $this->yelCartHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $this->yelCartHome;
    }

    public function statYelCartAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            $this->yelCartAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $this->yelCartAway;
    }

    public function teamEmbl($playHome, $team){

        //Формируем массив из названий команд(ключи) и эмблемами
        $foo = [];
        foreach ($playHome as $game) {
            $foo[$game['teamHome']['team_name']] = $game['teamHome']['team_embl'];
        }

//        Возвращаем ссылку на эмблему по названию команды - $team
        foreach ($foo as $k => $game){
            if ($k == $team){
                $foo = $game;
            }
        }

        return $foo;

    }

    public function statSum($scoreHome, $scoreAway, $count=0){

        //суммируем значения дома и на выезде для каждой команды
        foreach ($scoreHome as $k => $home){
            foreach ($scoreAway as $n => $away){
                if ($k == $n){
                    $this->score[$k] = $home + $away;
                }
            }
        }

        if ($count == 0){
            return $this->score;
        }else
        //Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
        foreach ($this->score as &$value){
            //Вычисляем количество сыгранных туров
            $value = $value / round($count/10);
            $value = round($value, 2);
        }
        return $this->score;
    }

    public function statOwnGoalSum($scoreHome, $scoreAway, $count=0){

        //суммируем значения дома и на выезде для каждой команды
        foreach ($scoreHome as $k => $home){
            foreach ($scoreAway as $n => $away){
                if ($k == $n){
                    $this->ownGoal[$k] = $home + $away;
                }
            }
        }

        if ($count == 0){
            return $this->ownGoal;
        }else
            //Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
            foreach ($this->ownGoal as &$value){
                //Вычисляем количество сыгранных туров
                $value = $value / round($count/10);
                $value = round($value, 2);
            }
        return $this->ownGoal;

    }

//    public function statSumMatch($array, $count){
//        //Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
//        foreach ($array as &$value){
//            //Вычисляем количество сыгранных туров
//            $value = $value / round($count/10);
//            return $value = round($value, 2);
//        }
//    }

    public function statMax($array){
        //Вычисляем максимальное значение в массиве
        return $team_max = max($array);
    }

    public function statMaxTeam($array){
        //Определяем команду, с наибольшим коэфициентом
        return $team = array_search(max($array), $array);
    }

//        [points] => Array
//        (
    //        [Барселона] => Array
    //              [points] => 17
    //              [game] => 8
    //              [goals] => 22
    //              [ownGoals] => 9
    //        [Севилья] => 17
    //        [Реал Мадрид] => 15
    //        [Атлетико] => 15
    //        [Вильяреал] => 13
    //        [Лас-Пальмас] => 12
    //        [Эйбар] => 11
    //        [Реал Сосьедад] => 10
    //        [Сельта] => 10
    //        [Атлетик] => 10
    //        [Леганес] => 10
    //        [Депортиво] => 8
    //        [Бетис] => 8
    //        [Малага] => 8
    //        [Спортинг] => 7
    //        [Эспаньол] => 7
    //        [Валенсия] => 7
    //        [Алавес] => 7
    //        [Осасуна] => 3
    //        [Гранада] => 2
//        )

    public function getTable($playHome, $goal, $ownGoal){
        //        Получаем отсортированный массив в виде Команда => значение: игры => X, очки => X, голы => X, пропголы => X
        foreach ($playHome as $game){
        //        метод подсчёта очков для каждой команды в таблице
            if ($game['home_score_full'] > $game['away_score_full']){
                $this->points[$game['teamHome']['team_name']]['points'] += 3;
            }elseif ($game['home_score_full'] < $game['away_score_full']){
                $this->points[$game['teamAway']['team_name']]['points'] += 3;
            }elseif ($game['home_score_full'] == $game['away_score_full']){
                $this->points[$game['teamHome']['team_name']]['points'] += 1;
                $this->points[$game['teamAway']['team_name']]['points'] += 1;
            }

            //        Сортируем массив с командами для вывода в таблицу чемпионата по убыванию по полю "очки"
            //        array_multisort($this->points, SORT_NUMERIC, SORT_DESC );
            $tmp = [];
            foreach ($this->points as $k => $item){
                $tmp[$k][] = $item[$k];
                $tmp['points'][] = $item['points'];
            }
            array_multisort($tmp['points'], SORT_DESC, $this->points);

            //        Подсчитываем количество сыгранных матчей каждой командой
            if ($game['home_team_id']){
                $this->points[$game['teamHome']['team_name']]['games'] += 1;
            }

            if ($game['away_team_id']){
                $this->points[$game['teamAway']['team_name']]['games'] += 1;
            }

            //        Подсчитываем количество забитых и пропущенных мячей каждой командой
            if ($game['home_team_id']){
                $this->points[$game['teamHome']['team_name']]['goal'] = $goal[$game['teamHome']['team_name']];
            }

            if ($game['away_team_id']){
                $this->points[$game['teamAway']['team_name']]['ownGoal'] = $ownGoal[$game['teamAway']['team_name']];
            }
        }

        return $this->points;

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'team_id' => 'ID команды',
            'team_name' => 'Название команды',
            'team_embl' => 'Эмблема команды',
            'team_stadium' => 'Стадион',
            'team_league' => 'Чемпионат',
        ];
    }
}
