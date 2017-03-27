<?php

namespace app\controllers;

use app\models\Parse;

class ScorerController extends AppController
{

    /*  Константы для методов парсинга бомбардиров и ассистентов на странице результатов чемпионата */
    const SCORERS = 0;
    const ASSIST = 1;
    /** счётчик с которого мы начинаем отчёт кол-ва array_push! */
    const COUNT = 1;

    /**
     * @return string - передаём во views массив $table c данными о бомбардирах и ассисентах и champ лигу где парсим
     * Экшен для показа списка бомбардиров и ассистентов. $id - № id чемпионата
     */
    public function actionScorers()
    {

        /* @var  $id - получаем id чемпионата из массива _GET */
        $id = \Yii::$app->request->get('id');
        /* Определяем ссылку, где будем парсить таблицу по переменной id */
        $url = '';
        switch ($id) {
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

        /* Эмулируем работу браузера с помощью curl*/
        $dom = curl_get($url);

        /* Создаем объект phpQuery */
        $score = \phpQuery::newDocumentHTML($dom);
        $score = pq($score);

        /*Вызываем метод модели parse для парсинга данных о бомбардирах! self::COUNT - счётчик с которого мы начинаем
         отчёт кол-ва array_push- 1. Посл. параметр - счётчик кол-ва раз сколько мы будем вызывать arrayPush.
         3 - для бомбардиров(голы, голы с пенальти, кол-во сыгранных матчей),2 - для ассистентов (ассисты, кол-во сыгранных матчей)*/
        $table['score'] = parse::scorers($score, self::SCORERS, self::COUNT, 3);

        //Вызываем метод модели parse для парсинга данных о ассистентах
        $table['assist'] = parse::scorers($score, self::ASSIST, self::COUNT, 2);

        return $this->render('@app/views/play/scorers.php', [
            'scorer' => $table,
            'champ' => $champ
        ]);
    }

}