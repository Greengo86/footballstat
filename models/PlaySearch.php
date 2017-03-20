<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Play;

/**
 * PlaySearch represents the model behind the search form about `app\models\Play`.
 */
class PlaySearch extends Play
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'league_id', 'home_team_id', 'away_team_id', 'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal', 'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside', 'h_tid_yellow_cart', 'a_tid_yellow_cart', 'h_tid_red_cart', 'a_tid_red_cart'], 'integer'],
            [['year', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Play::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'year' => $this->year,
            'date' => $this->date,
            'league_id' => $this->league_id,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'home_score_full' => $this->home_score_full,
            'away_score_full' => $this->away_score_full,
            'h_tid_posses' => $this->h_tid_posses,
            'a_tid_posses' => $this->a_tid_posses,
            'h_tid_shot_on_goal' => $this->h_tid_shot_on_goal,
            'a_tid_shot_on_goal' => $this->a_tid_shot_on_goal,
            'h_tid_foul' => $this->h_tid_foul,
            'a_tid_foul' => $this->a_tid_foul,
            'h_tid_corner' => $this->h_tid_corner,
            'a_tid_corner' => $this->a_tid_corner,
            'h_tid_offside' => $this->h_tid_offside,
            'a_tid_offside' => $this->a_tid_offside,
            'h_tid_yellow_cart' => $this->h_tid_yellow_cart,
            'a_tid_yellow_cart' => $this->a_tid_yellow_cart,
            'h_tid_red_cart' => $this->h_tid_red_cart,
            'a_tid_red_cart' => $this->a_tid_red_cart,
        ]);

        return $dataProvider;
    }
}
