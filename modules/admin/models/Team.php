<?php

namespace app\modules\admin\models;

use Yii;
use app\models\League;

/**
 * This is the model class for table "team".
 *
 * @property integer $team_id
 * @property string $team_name
 * @property string $team_embl
 * @property string $team_stadium
 * @property integer $team_league
 */
class Team extends \yii\db\ActiveRecord
{

    public $embl;

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
            [['team_name', 'team_stadium', 'team_league', 'team_embl'], 'required'],
            [['team_embl'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['team_name'], 'string', 'max' => 50],
            [['team_stadium'], 'string', 'max' => 30]
        ];
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

    public function getPlayHome()
    {
        return $this->hasOne(Play::className(), ['home_team_id' => 'team_id']);
    }

    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'team_league']);
    }
}
