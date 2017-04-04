<?php

namespace app\commands\controllers;

use app\models\Parse;
use Yii;
use yii\filters\AccessControl;


class LeagueParseController extends ParseController
{

//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
//    }

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


    /**
     * @return string - возвращаем массив для записи в бд! Только для тестирования
     * Экшен для парсинга Испанского чемпионата
     */
    public function actionSpain() {


        /** url странички с которой будем парсить - в данном случае "Испания"*/
        $url = 'http://soccer365.ru/competitions/16/';

        /** Устанавливаем счётчик $k переходов по матчам по количеству игр в туре! В Испании игр 10, отсчёт начиная с 0 - 10
        Что парсим, зависит от передаваемой константы LIVE или PLAY: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Передаём также строку 'spain' - В завимости от неё модель определит каким образом "разбирать" дату и время на странице матча.
        У матчей разных чемпионатов разные формата разбора*/
        $k = 9;
        $result = parent::actionLive($url, $k, self::PLAY, self::PRIMERA);

        foreach ($result as $value) {
            var_dump($value);
        }

        return $this->render('spain', [
            'result' => $result,
        ]);

    }

    /**
     * @return string - возвращаем массив для записи в бд! Только для тестирования
     * Экшен для парсинга Англиского чемпионата
     */
    public function actionEngland() {


        /** url странички с которой будем парсить - в данном случае "Англия" - даём ссылку со сдвигом временной
         * зоны на 3 часа - для временной зоны Европа/Москва
         */
        $url = 'http://soccer365.ru/competitions/12/';

        /** Устанавливаем счётчик $k переходов по матчам по количеству игр в туре! В Англии игр 10, отсчёт начиная с 0 - 10
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Здесь в отличии от Испании 4-ый аргумент не передаём, т.к. он не обязательный и
        поэтому модель будет парсить под формат чемпионата Англии. В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча. У матчей разных чемпионатов разные формата разбора*/
        $k = 9;
        $result = parent::actionLive($url, $k, self::PLAY, self::PREMIRLIGA);

        foreach ($result as $value) {
            var_dump($value);
        }

        return $this->render('england', [
            'result' => $result,
        ]);

    }

    /**
     * @return string - возвращаем массив для записи в бд! Только для тестирования
     * Экшен для парсинга Немецкого чемпионата
     */
    public function actionGermany() {


        /** url странички с которой будем парсить - в данном случае "Германия" - даём ссылку со сдвигом временной
         * зоны на 3 часа - для временной зоны Европа/Москва
         * */
        $url = 'http://soccer365.ru/competitions/17/';
        /** Устанавливаем счётчик $k переходов по матчам по количеству игр в туре! В Германии игр 9, отсчёт начиная с 0 - 9
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Здесь в отличии от Испании 4-ый аргумент не передаём, т.к. он не обязательный и
        поэтому модель будет парсить под формат чемпионата Англии. В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча. У матчей разных чемпионатов разные формата разбора*/
        $k = 8;

        $result = parent::actionLive($url, $k, self::PLAY, self::BUNDESLIGA);

        foreach ($result as $value) {
            var_dump($value);
        }

        return $this->render('germany', [
//            'teamhome' => $team_home,
//            'teamhomescore' => $team_home_score,
//            'teamaway' => $team_away,
            'result' => $result,
        ]);

    }

}