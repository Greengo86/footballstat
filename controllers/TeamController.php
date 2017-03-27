<?php

namespace app\controllers;

use app\models\Play;
use Yii;
use app\models\Team;
use app\models\TeamSearch;
use yii\filters\VerbFilter;
use yii\web\HttpException;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends AppController
{

    public $stat;
    public $home;
    public $away;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            [
//                'class' => 'yii\filters\PageCache',
//                'only' => ['team'],
//                'duration' => 3600 * 24 * 30,
//                'variations' => [
//                    \Yii::$app->request->get('t'),
//                ],
//                'dependency' => [
//                    'class' => 'yii\caching\DbDependency',
//                    'sql' => 'SELECT MAX(created_at) FROM play',
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * $id - id чемпионата
     * $t - id команды
     * @return string - передаём в вид team $id - id команды, $t id чемпионата, матчи команды дома - $teamHome и
     * на выезде $teamAway, последние 3 матча дома $limitHome и на выезде $limitAway, кол-во матчей дома $playCountHome и
     * количество матчей команды на выезде $playCountAway. Массив $stat[] содержит средние статистические значения
     * @throws HttpException - выдаём исключение, если такой команды не найдено
     * Экшен, который формирует среднеарифметические статистические показатели каждой команды во всех лигах
     */
    public function actionTeam()
    {
        //$id - id чемпионата, $t - id команды
        $id = Yii::$app->request->get('id');
        $t = Yii::$app->request->get('t');
//        Все матчи дома и на выезде
        $teamHome = Play::find()->asArray()->with('teamHome', 'league')->where(['league_id' => $id, 'home_team_id' => $t])->all();
        $teamAway = Play::find()->asArray()->with('teamAway')->where(['league_id' => $id, 'away_team_id' => $t])->all();
//        Последние 3 матча дома и на выезде
        $limitHome = Play::find()->asArray()->with('teamHome', 'teamAway', 'league')->where(['league_id' => $id, 'home_team_id' => $t])->limit(3)->all();
        $limitAway = Play::find()->asArray()->with('teamAway', 'teamHome', 'league')->where(['league_id' => $id, 'away_team_id' => $t])->limit(3)->all();
        $playCountHome = Play::find()->where(['home_team_id' => $t])->count();
        $playCountAway = Play::find()->where(['away_team_id' => $t])->count();


        //Вычисляем средние значения результативности дома, на выезде и общие. Сумму значений делим на количество проведённых матчей
        $model = new Team();
        foreach ($teamHome as $home) {

            $this->stat['score_home_full'] += $model->average($home['home_score_full'], $playCountHome);
            $this->home['home_score_full'] += $home['home_score_full'];

            $this->stat['shot_home'] += $model->average($home['h_tid_shot_on_goal'], $playCountHome);
            $this->home['shot_home'] += $home['h_tid_shot_on_goal'];

            $this->stat['h_posses'] += $model->average($home['h_tid_posses'], $playCountHome);
            $this->home['h_tid_posses'] += $home['h_tid_posses'];

            $this->stat['h_foul'] += $model->average($home['h_tid_foul'], $playCountHome);
            $this->home['h_tid_foul'] += $home['h_tid_foul'];

            $this->stat['h_corner'] += $model->average($home['h_tid_corner'], $playCountHome);
            $this->home['h_tid_corner'] += $home['h_tid_corner'];

            $this->stat['h_offside'] += $model->average($home['h_tid_offside'], $playCountHome);
            $this->home['h_tid_offside'] += $home['h_tid_offside'];

            $this->stat['h_yellow'] += $model->average($home['h_tid_yellow_cart'], $playCountHome);
            $this->home['h_tid_yellow_cart'] += $home['h_tid_yellow_cart'];

            $this->stat['h_red'] += $model->average($home['h_tid_red_cart'], $playCountHome);
            $this->home['h_tid_red_cart'] += $home['h_tid_red_cart'];
        }

        foreach ($teamAway as $away) {

            $this->stat['score_away_full'] += $model->average($away['away_score_full'], $playCountAway);
            $this->away['away_score_full'] += $away['away_score_full'];
            $this->stat['score_full_f'] = $model->averageFull($this->home['home_score_full'], $this->away['away_score_full'], $playCountHome, $playCountAway);

            $this->stat['shot_away'] += $model->average($away['a_tid_shot_on_goal'], $playCountAway);
            $this->away['a_tid_shot_on_goal'] += $away['a_tid_shot_on_goal'];
            $this->stat['shot_full'] = $model->averageFull($this->home['shot_home'], $this->away['a_tid_shot_on_goal'], $playCountHome, $playCountAway);

            $this->stat['a_posses'] += $model->average($away['a_tid_posses'], $playCountAway);
            $this->away['a_tid_posses'] += $away['a_tid_posses'];
            $this->stat['posses_full'] = $model->averageFull($this->home['h_tid_posses'], $this->away['a_tid_posses'], $playCountHome, $playCountAway);

            $this->stat['a_foul'] += $model->average($away['a_tid_foul'], $playCountAway);
            $this->away['a_tid_foul'] += $away['a_tid_foul'];
            $this->stat['foul_full'] = $model->averageFull($this->home['h_tid_foul'], $this->away['a_tid_foul'], $playCountHome, $playCountAway);

            $this->stat['a_corner'] += $model->average($away['a_tid_corner'], $playCountAway);
            $this->away['a_tid_corner'] += $away['a_tid_corner'];
            $this->stat['corner_full'] = $model->averageFull($this->home['h_tid_corner'], $this->away['a_tid_corner'], $playCountHome, $playCountAway);

            $this->stat['a_offside'] += $model->average($away['a_tid_offside'], $playCountAway);
            $this->away['a_tid_offside'] += $away['a_tid_offside'];
            $this->stat['offside_full'] = $model->averageFull($this->home['h_tid_offside'], $this->away['a_tid_offside'], $playCountHome, $playCountAway);

            $this->stat['a_yellow'] += $model->average($away['a_tid_yellow_cart'], $playCountAway);
            $this->away['a_tid_yellow_cart'] += $away['a_tid_yellow_cart'];
            $this->stat['yellow_full'] = $model->averageFull($this->home['h_tid_yellow_cart'], $this->away['a_tid_yellow_cart'], $playCountHome, $playCountAway);

            $this->stat['a_red'] += $model->average($away['a_tid_red_cart'], $playCountAway);
            $this->away['a_tid_red_cart'] += $away['a_tid_red_cart'];
            $this->stat['red_full'] = $model->averageFull($this->home['h_tid_red_cart'], $this->away['a_tid_red_cart'], $playCountHome, $playCountAway);
        }

        if (empty($teamHome) || empty($teamAway)) {
            throw new HttpException('404', 'Указанный Вами команда отсутствует');
        }

        return $this->render('team', [
            'teamHome' => $teamHome,
            'limitHome' => $limitHome,
            'limitAway' => $limitAway,
            'id' => $id,
            't' => $t,
            'stat' => $this->stat,
        ]);
    }

}
