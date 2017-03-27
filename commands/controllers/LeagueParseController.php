<?php

namespace app\commands\controllers;

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

    /* LIVE - .block_header:eq(0) - недавно сыгранные матчи */
    const LIVE = 0;
    /* PLAY - .block_header:eq(1) - матчи, сыгранные 2-3 назад*/
    const PLAY = 1;
    /* Константы для управления правильным разбором даты и времени на странице статистики матча*/
    const PRIMERA = 'spain';
    const PREMIRLIGA = 'england';
    const BUNDESLIGA = 'germany';

    public $spain = [];
    public $england = [];
    public $germany = [];
    public $layout = false;

    /**
     * @return string - подготовленный массив, который состоит из матчей Испанской Примеры
     * Передаём во view для отладки консольного приложения
     */
    public function actionSpain()
    {

        /* url странички с которой будем парсить - в данном случае "Испания"*/
        $url = 'soccer365.ru/competitions/16/';

        /* Устанавливаем счётчик $i переходов по матчам по количеству игр в туре! В Испании игр 10, отсчёт начиная с 0 - 10
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Передаём также строку 'spain' - В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча
        У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой*/
        $i = 9;
        $result = parent::actionLive($url, $i, self::PLAY, self::PRIMERA);
        echo 'heeey';

        return $this->render('spain', [
            'result' => $result,
        ]);
    }

    /**
     * @return string - подготовленный массив, который состоит из матчей Английской Премьер Лиги
     * Передаём во view для отладки консольного приложения
     */
    public function actionEngland()
    {

        /* url странички с которой будем парсить - в данном случае "Англия" - даём ссылку со сдвигом временной
        зоны на 3 часа - для временной зоны Европа/Москва*/
        $url = 'soccer365.ru/competitions/12/';

        /* Устанавливаем счётчик $i переходов по матчам по количеству игр в туре! В Англии игр 10, отсчёт начиная с 0 - 10
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Здесь в отличии от Испании 4-ый аргумент не передаём, т.к. он не обязательный и
        поэтому модель будет парсить под формат чемпионата Англии. В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча.
        У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой*/
        $i = 9;
        $result = parent::actionLive($url, $i, self::LIVE, self::PREMIRLIGA);

        return $this->render('england', [
            'result' => $result,
        ]);
    }

    /**
     * @return string - подготовленный массив, который состоит из матчей Немецкой БундесЛиги
     * Передаём во view для отладки консольного приложения
     */
    public function actionGermany()
    {

        /* url странички с которой будем парсить - в данном случае "Германия" - даём ссылку со сдвигом временной
        зоны на 3 часа - для временной зоны Европа/Москва */
        $url = 'soccer365.ru/competitions/17/';

        /* Устанавливаем счётчик $i переходов по матчам по количеству игр в туре! В Германии игр 9, отсчёт начиная с 0 - 9
        Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
        Здесь в отличии от Испании 4-ый аргумент не передаём, т.к. он не обязательный и
        поэтому модель будет парсить под формат чемпионата Англии. В завимости от неё модель определит
        каким образом "разбирать" дату и время на странице матча.
        У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой*/
        $i = 8;

        $result = parent::actionLive($url, $i, self::PLAY, self::BUNDESLIGA);

        return $this->render('germany', [
            'result' => $result,
        ]);
    }

}