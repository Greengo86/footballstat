<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "league".
 *
 * @property integer $id
 * @property string $league
 */
class League extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'league';
    }

    public function getPlays()
    {
        return $this->hasMany(League::className(), ['league_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['league'], 'required'],
            [['league'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'league' => 'League',
        ];
    }
}
