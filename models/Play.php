<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "play".
 *
 * @property string $id
 * @property string $year
 * @property string $date
 * @property integer $league_id
 * @property integer $home_team_id
 * @property integer $away_team_id
 * @property integer $home_score_full
 * @property integer $away_score_full
 * @property integer $h_tid_posses
 * @property integer $a_tid_posses
 * @property integer $h_tid_shot_on_goal
 * @property integer $a_tid_shot_on_goal
 * @property integer $h_tid_foul
 * @property integer $a_tid_foul
 * @property integer $h_tid_corner
 * @property integer $a_tid_corner
 * @property integer $h_tid_offside
 * @property integer $a_tid_offside
 * @property integer $h_tid_yellow_cart
 * @property integer $a_tid_yellow_cart
 * @property integer $delay
 * @property integer $h_tid_red_cart
 * @property integer $a_tid_red_cart
 */
class Play extends ActiveRecord
{

    //Устанавливаем константу для консольного приложения(парсинга мачтей), как признак, что игра добавлена в бд
    const RECORD_INSERTED = 'RECORD_INSERTED';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Присоединяем обработчик события к статической функции method_Record_Insert(), где будем рассылать письма
     * Здесь мы ловим, то событие из ParseController, которое инициируется при записи данных в бд и вызываем статическую ф-цию 'method_Record_Insert'
     */
    public function init()
    {
        $this->on(self::RECORD_INSERTED, [$this, 'method_Record_Insert']);

    }

    /**
     * Статический метод, который мы вызываем с помощью событие, а именно запись в базу данных из контроллера
     * ParseController и отправляем почту
     */
    public static function method_Record_Insert($event)
    {

        Yii::$app->mailer->compose()
            ->setFrom('from@domain.com')
            ->setTo('to@domain.com')
            ->setSubject('Тема сообщения')
            ->setTextBody('Текст сообщения')
            ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
            ->send();
        var_dump($event->play_insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'play';
    }

//    public function getTeam()
//    {
//        return $this->hasOne(Team::className(), ['team_league' => 'league_id']);
//    }

    public function getTeamHome()
    {
        return $this->hasOne(Team::className(), ['team_id' => 'home_team_id']);
    }

    public function getTeamAway()
    {
        return $this->hasOne(Team::className(), ['team_id' => 'away_team_id']);
    }

    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
    }

    public function getid()
    {
        return $this->hasOne(Team::className(), ['team_id' => 'home_team_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'date', 'delay'], 'safe'],
            [['date', 'league_id', 'home_team_id', 'away_team_id', 'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal', 'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside', 'h_tid_yellow_cart', 'a_tid_yellow_cart'], 'required'],
            [['league_id', 'home_team_id', 'away_team_id', 'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal', 'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside', 'h_tid_yellow_cart', 'a_tid_yellow_cart', 'h_tid_red_cart', 'a_tid_red_cart', 'delay'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'date' => 'Date',
            'league_id' => 'League ID',
            'home_team_id' => 'Home Team ID',
            'away_team_id' => 'Away Team ID',
            'home_score_full' => 'Home Score Full',
            'away_score_full' => 'Away Score Full',
            'h_tid_posses' => 'H Tid Posses',
            'a_tid_posses' => 'A Tid Posses',
            'h_tid_shot_on_goal' => 'H Tid Shot On Goal',
            'a_tid_shot_on_goal' => 'A Tid Shot On Goal',
            'h_tid_foul' => 'H Tid Foul',
            'a_tid_foul' => 'A Tid Foul',
            'h_tid_corner' => 'H Tid Corner',
            'a_tid_corner' => 'A Tid Corner',
            'h_tid_offside' => 'H Tid Offside',
            'a_tid_offside' => 'A Tid Offside',
            'h_tid_yellow_cart' => 'H Tid Yellow Cart',
            'a_tid_yellow_cart' => 'A Tid Yellow Cart',
            'h_tid_red_cart' => 'H Tid Red Cart',
            'a_tid_red_cart' => 'A Tid Red Cart',
            'delay' => 'Match Delayed',
        ];
    }
}
