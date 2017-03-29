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
    public $games;

    public static $yelCartHome;
    public static $yelCartAway;
    public static $possesHome;
    public static $possesAway;
    public static $foulHome;
    public static $foulAway;
    public static $cornerHome;
    public static $cornerAway;
    public static $offsideHome;
    public static $offsideAway;
    public static $points;

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


    /**
     * @return int
     * Вернём среднее арифметическое для каждого статического показателя команды
     */
    public function average($stat, $qty)
    {
           return round($stat / $qty, 2);
    }

    public function averageFull($statHome, $statAway, $qtyHome, $qtyAway = 0)
    {
        return round(($statHome + $statAway) / ($qtyHome + $qtyAway), 2);
    }

    //Методы для формирования StatWidget на главной странице
    public static function statFoulHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            self::$foulHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return self::$foulHome;

    }

    public static function statFoulAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            self::$foulAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return self::$foulAway;

    }

    public static function statCornerHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            self::$cornerHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return self::$cornerHome;

    }

    public static function statCornerAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            self::$cornerAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return self::$cornerAway;
    }

    public static function statPossesHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            self::$possesHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return self::$possesHome;
    }

    public static function statPossesAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            self::$possesAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return self::$possesAway;
    }

    public static function statOffsideHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            self::$offsideHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return self::$offsideHome;
    }

    public static function statOffsideAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            self::$offsideAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return self::$offsideAway;
    }

    public static function statYelCartHome($playHome, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            self::$yelCartHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return self::$yelCartHome;
    }

    public static function statYelCartAway($playAway, $value){

        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            self::$yelCartAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return self::$yelCartAway;
    }

    public static function teamEmbl($playHome, $team)
    {

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

    /**
     * @param $array - массив, в котором необходимо вычислить максимальное значение
     * @return mixed - возвращаем максимальный элемент в массиве
     * Метод для вычисления максимальное значения в массиве
     */
    public static function statMax($array)
    {
        //Вычисляем максимальное значение в массиве
        return $team_max = max($array);
    }

    /**
     * @param $array - массив, в котором необходимо вычислить значение с наибольшим коэффициентом
     * @return mixed - возвращаем значение с наибольшим коэффициентом
     * Метод для вычисления значение с наибольшим коэффициентом
     */
    public static function statMaxTeam($array)
    {
        //Определяем команду, с наибольшим коэфициентом
        return $team = array_search(max($array), $array);
    }

//    Методы для формирования турнирных таблиц для каждого чемпионата на главной странице
    /**
     * @param $playHome - массив проведёных домашних матчей
     * @param $value - статистическое значение. В данном случае home_score_full - забитые мячи домашней команды
     * @return mixed - сформированный массив из ключа(команды) и значения (забитые мячи)
     */
    public static function statGoalHome($playHome, $value)
    {

        $goalHome = [];
        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            $goalHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $goalHome;

    }

    /**
     * @param $playAway - массив проведёных гостевых матчей
     * @param $value - статистическое значение. В данном случае away_score_full - забитые мячи домашней команды
     * @return mixed - сформированный массив из ключа(команды) и значения (забитые мячи)
     */
    public static function statGoalAway($playAway, $value)
    {

        $goalAway = [];
        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            $goalAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $goalAway;

    }

    /**
     * @param $playHome - массив проведёных домашних матчей
     * @param $value - статистическое значение. В данном случае away_score_full - забитые мячи домашней команды
     * В данном методе подсчитываем голы пропущёные дома, поэтому принимаем значение away_score_full
     * @return mixed - сформированный массив из ключа(команды) и значения (пропущенные мячи)
     */
    public static function statGoalOwnHome($playHome, $value)
    {

        $goalOwnHome = [];
        //Формируем массив из названий команд(ключи) и статистическими значениями дома
        foreach ($playHome as $game) {
            $goalOwnHome[$game['teamHome']['team_name']] += $game[$value];
        }

        return $goalOwnHome;

    }

    /**
     * @param $playAway - массив проведёных гостевых матчей
     * @param $value - статистическое значение. В данном случае home_score_full - забитые мячи домашней команды
     * В данном методе подсчитываем голы пропущёные дома, поэтому принимаем значение home_score_full
     * @return mixed - сформированный массив из ключа(команды) и значения (пропущенные мячи)
     */
    public static function statGoalOwnAway($playAway, $value)
    {

        $goalOwnAway = [];
        //Формируем массив из названий команд(ключи) и статистическими значениями на выезде
        foreach ($playAway as $game) {
            $goalOwnAway[$game['teamAway']['team_name']] += $game[$value];
        }

        return $goalOwnAway;

    }

    /**
     * @param $scoreHome
     * @param $scoreAway
     * @param int $count
     * @return mixed
     * Подсчитываем сумму каких-либо статистических показателей... Если не передаём параметр $count, то подчитываем забитые
     * мячи дома и на выезде. Если передаём $count, то подсчитываем стат. показатели в среднем(делим на кол-во проведённых матчей)
     * и округляем до 2 знаков для виджета Stat
     */
    public static function statSum($scoreHome, $scoreAway, $count = 0)
    {

        //суммируем значения дома и на выезде для каждой команды
        foreach ($scoreHome as $k => $home){
            foreach ($scoreAway as $n => $away){
                if ($k == $n){
                    $array[$k] = $home + $away;
                }
            }
        }

        if ($count == 0) {
            return $array;
        }else
            //Вычисляем среднее стат. значение за матч (делим на кол-во сыгранных матчей) и округляем до 2 знаков
            foreach ($array as &$value) {
            //Вычисляем количество сыгранных матчей
            $value = $value / round($count/10);
            $value = round($value, 2);
        }
        return $array;
    }

    /**
     * @param $scoreOwnHome
     * @param $scoreOwnAway
     * @param int $count
     * @return mixed
     * Подсчитываем сумму каких-либо статистических показателей... Если не передаём параметр $count, то подчитываем пропущенные
     * мячи дома и на выезде. Если передаём $count, то вычисляем пропущённые мячи в среднем(делим на кол-во проведённых матчей)
     * и округляем до 2 знаков для виджета Stat
     */
    public static function statOwnGoalSum($scoreOwnHome, $scoreOwnAway, $count = 0)
    {

        //суммируем значения дома и на выезде для каждой команды
        foreach ($scoreOwnHome as $k => $home) {
            foreach ($scoreOwnAway as $n => $away) {
                if ($k == $n){
                    $array[$k] = $home + $away;
                }
            }
        }

        if ($count == 0){
            return $array;
        }else
            //Вычисляем среднее стат. значение за матч (делим на сыгранных кол-во матчей) и округляем до 2 знаков
            foreach ($array as &$value) {
                //Вычисляем количество сыгранных туров
                $value = $value / round($count/10);
                $value = round($value, 2);
            }
        return $array;
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

    /**
     * @param $playHome
     * @param $goal
     * @param $ownGoal
     * @return mixed - возвращаем сформированный отсортированный массив, для вывода в таблицу каждого из чемпионатов
     */
    public static function getTable($playHome, $goal, $ownGoal)
    {

        //Обнуляем статическое свойство перед каждый проходом
        self::$points = [];
        //        Получаем отсортированный массив в виде Команда => значение: игры => X, очки => X, голы => X, пропголы => X
        foreach ($playHome as $game){
            //     подсчитываем очки для каждой команды в таблице
            if ($game['home_score_full'] > $game['away_score_full']){
                self::$points[$game['teamHome']['team_name']]['points'] += 3;
            }elseif ($game['home_score_full'] < $game['away_score_full']){
                self::$points[$game['teamAway']['team_name']]['points'] += 3;
            }elseif ($game['home_score_full'] == $game['away_score_full']){
                self::$points[$game['teamHome']['team_name']]['points'] += 1;
                self::$points[$game['teamAway']['team_name']]['points'] += 1;
            }

            //        Сортируем массив с командами для вывода в таблицу чемпионата по убыванию по полю "очки"
            //        array_multisort(self::$points, SORT_NUMERIC, SORT_DESC );
            $tmp = [];
            foreach (self::$points as $k => $item) {
                $tmp[$k][] = $item[$k];
                $tmp['points'][] = $item['points'];
            }
            array_multisort($tmp['points'], SORT_DESC, self::$points);

            //        Подсчитываем количество сыгранных матчей каждой командой
            if ($game['home_team_id']){
                self::$points[$game['teamHome']['team_name']]['games'] += 1;
            }

            if ($game['away_team_id']){
                self::$points[$game['teamAway']['team_name']]['games'] += 1;
            }

            //        Подсчитываем количество забитых и пропущенных мячей каждой командой
            if ($game['home_team_id']){
                self::$points[$game['teamHome']['team_name']]['goal'] = $goal[$game['teamHome']['team_name']];
            }

            if ($game['away_team_id']){
                self::$points[$game['teamAway']['team_name']]['ownGoal'] = $ownGoal[$game['teamAway']['team_name']];
            }
        }

        return self::$points;

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
