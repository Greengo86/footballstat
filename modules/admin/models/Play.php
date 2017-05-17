<?php

namespace app\modules\admin\models;

use Yii;
use app\models\League;
use app\models\Team;

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
class Play extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'play';
    }

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

//    Функция для вывода лиги в detailView в admin/views/play/view
    public function getLeagueHtml($id)
    {
        switch ($id) {
            case 1:
                return 'Испания';
            case 2:
                return 'Англия';
            case 3:
                return 'Германия';
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'delay'], 'safe'],
            [['year', 'date', 'league_id', 'home_team_id', 'away_team_id', 'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal', 'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside', 'h_tid_yellow_cart', 'a_tid_yellow_cart'], 'required'],
            [['league_id', 'league_id', 'home_team_id', 'away_team_id', 'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal', 'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside', 'h_tid_yellow_cart', 'a_tid_yellow_cart', 'h_tid_red_cart', 'a_tid_red_cart', 'delay'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delay' => 'Перенесён ли Матч?',
            'year' => 'Год',
            'date' => 'Дата',
            'league_id' => 'Лига',
            'home_team_id' => 'Хозяева',
            'away_team_id' => 'Гости',
            'home_score_full' => 'Голы хозяев',
            'away_score_full' => 'Голы гостей',
            'h_tid_posses' => '% вл. дом',
            'a_tid_posses' => '% вл. гос',
            'h_tid_shot_on_goal' => 'Уд дом. к',
            'a_tid_shot_on_goal' => 'Уд гост. к',
            'h_tid_foul' => 'Фолы д.',
            'a_tid_foul' => 'Фолы г.',
            'h_tid_corner' => 'Угл д.',
            'a_tid_corner' => 'Угл г.',
            'h_tid_offside' => 'Офф д.',
            'a_tid_offside' => 'Офф г.',
            'h_tid_yellow_cart' => 'Ж.к. д.',
            'a_tid_yellow_cart' => 'Ж.к. г.',
            'h_tid_red_cart' => 'К.к. д.',
            'a_tid_red_cart' => 'К.к. г.',
        ];
    }
}
