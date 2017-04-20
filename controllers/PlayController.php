<?php

namespace app\controllers;

use app\models\Parse;
use app\models\Team;
use Yii;
use app\models\Play;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * @param $id - id чемпионата
     * @return string - возвращаем, массив игр - $play, id чемпионата и количество сыгранных туров
     * всего в чемпионате - $playCount
     * @throws HttpException - Исключение в том случае, если таких матчей не найдено
     * Экшен для показа последних 20 матчей чемпионата, который(чемпионат) определяем по $id
     */
    public function actionChamp($id)
    {

        $play = Play::find()->with('teamHome', 'teamAway', 'league')->indexBy('id')->asArray()->limit(20)->where(['league_id' => $id])->orderBy(['date' => SORT_ASC])->all();
        //Получаем количество сыгранных матчей для формирования меню для перехода по турам!
        $playCount = Play::find()->where(['league_id' => $id])->orderBy('id')->count();

        if (empty($play)) {
            throw new HttpException('404', 'Указанная Вами лига отсутствует');
        }

        return $this->render('champ', [
            'playCount' => $playCount,
            'id' => $id,
            'play' => $play,
        ]);
    }

    /**
     * @param $id - id чемпионата
     * @return string - возвращаем сериализованную переменную в вид для отображения последний 10 матчей чемпионата
     */
    public function actionLastGames()
    {

        $id = Yii::$app->request->get('id');
        $play = Play::find()->with('teamHome', 'teamAway', 'league')->indexBy('id')->asArray()->limit(10)->where(['league_id' => $id])->orderBy(['date' => SORT_DESC])->all();
        //Рендерим аяксом view champ и выводим на главную страницу последние 10 матчей 3 чемпионатов по клику в tab'е
        $html = $this->renderAjax('champ', [
            'playCount' => 10,
            /*Объявляем переменную main_page и передаём в view champ! Если она объявлена, это значит мы на главной странице
            и таблицу выбора тура чемпионата показывать не нужно*/
            'main_page' => 0,
            'id' => $id,
            'play' => $play,
        ]);
        return Json::encode($html);
    }

    /**
     * @param string $id - id матча! поле id в таблице play в бд
     * @return mixed - возвращаем в массиве матч $match и $id
     * Экшен показа одного матча
     */
    public function actionMatch($id)
    {
        $match = Play::find()->asArray()->with('teamHome', 'teamAway', 'league')->where(['id' => $id])->one();

        /* Отключаем лейаут для показа матча, т.к. будем показывать в модальном окне*/
        $this->layout = false;

        return $this->render('match', [
            'match' => $match,
            'id' => $id,
        ]);
    }

    /**
     * @param string $id - id чемпионата
     * $t - номер тура
     * @return mixed - возвращаем массив с играми в туре - $tour, кол-во сыгранных матчей $playCount,
     * номер тура $t и id чемпионата - $id
     * @throws HttpException - выдаём исключение, если такого тура не найдено
     * Экшен показа тура чемпионата
     */
    public function actionTour()
    {

        //$t - номер тура, $id - id чемпионата
        $id = Yii::$app->request->get('id');
        $t = Yii::$app->request->get('t');
        $tOff = (int)$t * 10 - 10;
        //Получаем выборку необходимого тура! Номер тура определяем по переменной $t из массива _GET
        $tour = Play::find()->with('teamHome', 'teamAway', 'league')->indexBy('id')->asArray()->limit(10)->offset($tOff)->where(['league_id' => $id])->all();
        //Получаем количество сыгранных матчей для формирования меню для перехода по турам!
        $playCount = Play::find()->where(['league_id' => $id])->orderBy('id')->count();

        if (empty($tour)) {
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
