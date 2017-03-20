<?php

namespace app\controllers;

use app\models\Parse;
use app\models\Team;
use Yii;
use app\models\Play;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * PlayController implements the CRUD actions for Play model.
 */
class PlayController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Play models.
     * @return mixed
     */

    public function actionChamp($id)
    {

        $play = Play::find()->with('teamHome', 'teamAway', 'league')->indexBy('id')->asArray()->limit(20)->where(['league_id' => $id])->orderBy(['date' => SORT_DESC])->all();
        //Получаем количество сыгранных матчей для формирования меню для перехода по турам!
        $playCount = Play::find()->orderBy('id')->count();

        if(empty($play)){
            throw new HttpException('404', 'Указанная Вами лига отсутствует');
        }

        return $this->render('champ', [
            'playCount' => $playCount,
            'id' => $id,
            'play' => $play,
        ]);
    }


    /**
     * Displays a single Play match.
     * @param string $id
     * @return mixed
     */
    public function actionMatch($id)
    {
        $match = Play::find()->asArray()->with('teamHome', 'teamAway', 'league')->where(['id' => $id])->one();

        $this->layout = false;

        return $this->render('match', [
            'match' => $match,
            'id' => $id,
        ]);
    }

    /**
     * Displays a matchtour.
     * $t - номер тура
     * @param string $id
     * @return mixed
     */
    public function actionTour()
    {

        //$t - номер тура, $id - id чемпионата
        $id = Yii::$app->request->get('id');
        $t = Yii::$app->request->get('t');
        $tOff = (int)$t * 10 - 10;
        //Получаем выборку необходимого тура! Номер тура определяем по переменной $t из массива GET
        $tour = Play::find()->with('teamHome', 'teamAway', 'league')->indexBy('id')->asArray()->limit(10)->offset($tOff)->where(['league_id' => $id])->all();
        //Получаем количество сыгранных матчей для формирования меню для перехода по турам!
        $playCount = Play::find()->orderBy('id')->count();

//        $team = Team::find()->andWhere(['team_name' => 'Валенсия'])->one();
//        var_dump($team->team_id);

        if(empty($tour)){
            throw new HttpException('404', 'Указанный Вами тур отсутствует');
        }

        return $this->render('tour', [
            'playCount' => $playCount,
            'tour' => $tour,
            'id' => $id,
            't' => $t,
        ]);
    }

    /**
     * Creates a new Play model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Play();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Play model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Play model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Play model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Play the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Play::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
