<?php

namespace app\controllers;

use app\models\Parse;
use yii\filters\AccessControl;


class LeagueParseController extends ParseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**  LIVE - .block_header:eq(0) - недавно сыгранные матчи */
    const LIVE = 0;
    /**  PLAY - .block_header:eq(1) - матчи, сыгранные 2-3 назад*/
    const PLAY = 1;

    /** Константы для управления правильным разбором даты и времени на странице статистики матча*/
    const PRIMERA = 'spain';
    const PREMIRLIGA = 'england';
    const BUNDESLIGA = 'germany';


    public $spain = [];
    public $england = [];
    public $germany = [];
    public $layout = false;


    public function actionSpain() {


        /** url странички с которой будем парсить - в данном случае "Испания"*/
        $url = 'soccer365.ru/competitions/16/';

        /** Устанавливаем счётчик $k переходов по матчам по количеству игр в туре! В Испании игр 10, отсчёт начиная с 0 - 10
         Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
         Передаём также строку 'spain' - В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча
        У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой*/
        $k = 9;
        $result = parent::actionLive($url, $k, self::PLAY, self::PRIMERA);


        return $this->render('spain', [
//            'teamhome' => $team_home,
//            'teamhomescore' => $team_home_score,
            'result' => $result,
        ]);

    }

    public function actionEngland() {


        /** url странички с которой будем парсить - в данном случае "Англия" - даём ссылку со сдвигом временной
         * зоны на 3 часа - для временной зоны Европа/Москва
         */
        $url = 'soccer365.ru/competitions/12/';

        /** Устанавливаем счётчик $k переходов по матчам по количеству игр в туре! В Англии игр 10, отсчёт начиная с 0 - 10
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Здесь в отличии от Испании 4-ый аргумент не передаём, т.к. он не обязательный и
        поэтому модель будет парсить под формат чемпионата Англии. В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча.
        У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой*/
        $k = 9;
        $result = parent::actionLive($url, $k, self::LIVE, self::PREMIRLIGA);

        return $this->render('england', [
//            'teamhome' => $team_home,
//            'teamhomescore' => $team_home_score,
//            'teamaway' => $team_away,
//            'teamawayscore' => $team_away_score,
//            'date' => $date,
            'result' => $result,
        ]);

    }

    public function actionGermany() {


        /** url странички с которой будем парсить - в данном случае "Германия" - даём ссылку со сдвигом временной
         * зоны на 3 часа - для временной зоны Европа/Москва
         * */
        $url = 'soccer365.ru/competitions/17/';
        /** Устанавливаем счётчик $k переходов по матчам по количеству игр в туре! В Германии игр 9, отсчёт начиная с 0 - 9
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Здесь в отличии от Испании 4-ый аргумент не передаём, т.к. он не обязательный и
        поэтому модель будет парсить под формат чемпионата Англии. В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча.
        У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой*/
        $k = 8;

        $result = parent::actionLive($url, $k, self::LIVE, self::BUNDESLIGA);


        return $this->render('germany', [
//            'teamhome' => $team_home,
//            'teamhomescore' => $team_home_score,
//            'teamaway' => $team_away,
            'result' => $result,
        ]);

    }

    /** Экшен для показа списка бомбардиров и ассистентов. $id - № id чемпионата */
    public function actionScorers()
    {

        $this->layout = 'main';
        /** @var  $id - id чемпионата */
        $id = \Yii::$app->request->get('id');
        /** Определяем ссылку, где будем парсить таблицу по переменной id */
        $url = '';
        switch ($id){
            case 1:
                $url = 'soccer365.ru/competitions/16/';
                break;
            case 2:
                $url = 'soccer365.ru/competitions/12/';
                break;
            case 3:
                $url = 'soccer365.ru/competitions/17/';
                break;
        }

        //Определяем лигу где парсим данные вызывая статический метод scorersChamp
        $champ = parse::scorersChamp($id);

        $scorer = parent::actionScorer($url);

        return $this->render('@app/views/play/scorers.php', [
            'scorer' => $scorer,
            'champ' => $champ
        ]);

    }



}