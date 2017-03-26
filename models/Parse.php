<?php

namespace app\models;


use yii\base\Model;
use Yii;

class Parse extends Model
{

    public $stat;
    public $href;
    public $team_home;
    public $team_away;

    public static $scorer;
    public static $count;

    /** Метод для определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную
     * для базы данных для записи матча*/
    public static function parseChamp($pq_m){

        $champ = trim($pq_m->find('.live_body #game_events .block_header')->text(), "\x00..\x1F");

        $champ = explode(', ', $champ)[0];

        if(preg_match('/Примера/', $champ)){
            $league = 1;
        }elseif(preg_match('/Премьер лига/', $champ)){
            $league = 2;
        }elseif(preg_match('/Бундеслига/', $champ)) {
            $league = 3;
        }
        return $league;

    }


    /** Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка! проходимся в цикле 10 раз - по количеству игр
     * в туре и записываем в массив $stat вызывая $play->parsePlay(), применяя функцию trim()
     * для обрезания ненужных пробелов и символов
     * parseLive В зависимости от параметра $res парсит недавно сыгранные матчи которые находятся в классе
     * .block_header:eq(0) или сыгранные 2-3 назад, которые находятся в классе .block_header:eq(1) на странице рез-тов
     */
    public function parseLive($team, $i, $res)
    {

        $this->stat['tour'] = trim($team->find('.block_header:eq(' . $res . ')')->text(), "\x00..\x1F");

        foreach ($team->find('.block_body_nopadding:eq(' . $res . ')') as $game){

            $game = pq($game);

            $this->stat['link'] = $game->find('.game_block:eq(' . $i . ') a:eq(0)')->attr('href');
            $this->stat['date'] = trim($game->find('.game_block:eq(' . $i . ') .game_ht .game_start:eq(0)')->text(), "\x00..\x1F");

            $this->stat['year'] = explode('.', $this->stat['date'])[2];

            /** Обрезаем полученые результаты в виде строки - 2 символа в начале строки у 'team_home' */
            $this->stat['team_home'] = substr($game->find('.game_block:eq(' . $i . ') .game_ht:eq(0) .game_team:eq(0)')->text(), 2);
            $this->stat['team_home_score'] = trim($game->find('.game_block:eq(' . $i . ') .game_ht:eq(0) .game_goals:eq(0)')->text(), "\x00..\x1F");
            /** Обрезаем полученые результаты в виде строки - 2 символа в конце строки у 'team_away' */
            $this->stat['team_away'] = substr($game->find('.game_block:eq(' . $i . ') .game_at:eq(0) .game_team:eq(0)')->text(),0, -2);
            $this->stat['team_away_score'] = trim($game->find('.game_block:eq(' . $i . ') .game_at:eq(0) .game_goals:eq(0)')->text(), "\x00..\x1F");

        }

        return $this->stat;
    }

    /** Парсим данные по ссылкам - статистика! проходимся в цикле - по количеству ссылок
     * в туре и записываем в массив $stat вызывая $play->parseStat(),применяя функцию trim() для обрезания ненужных
     * пробелов и символов. Используется только для парсинга статистики.
     * По принятым переменным $time_stat и $date_stat определяем каким образом разобрать дату и время.
    У матчей разных чемпионатов разные параметры парсинга времени и даты! Значения $time_stat, $date_stat -
    для каждого чемпионата будут свои*/
    public function parseStat($pq_m, $i, $league)
    {

        $this->stat['h_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(1)')->text();
        /** Определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную
         * для базы данных*/
        $this->stat['league_id'] = $this->parseChamp($pq_m[$i]);
        $head_date = trim($pq_m[$i]->find('.live_body #game_events .block_header')->text(), "\x00..\x1F");
        /** Если в спаршенной строке 'date'- не дата, а 'Перенесен' - записываем в поле delay - 1 true, если дата, то 0 - false */
        $this->stat['delay'] = $this->stat['date'] == 'Перенесен' ? 1 : 0;
        /**  Формируем year разбиваем по пробелу, а и по точке и берём только год
         * $time снова обрезаем по пробелу и берём ту часть, где время
         * date снова обрезаем по пробелу и берём дату в формает "05.02.17" и заменяем
         * функцией substr_replace часть строки начиная с 6-ой позиции подставляя год в нужном формате
         * собираем из предыдущих кусков и форматируем $app->formatter->asDatetime ['datetime']
         * под нужный формат в бд в виде "03.02.2017 22:45"
         * По принятым переменным $time_stat и $date_stat определяем каким образом разобрать дату и время.
        У матчей разных чемпионатов разный формат разбора даты и времени. В зависимости от принимаемых значений
        устанавливаем переменные для разбора даты и времени, т.к. они разные в строке-заголовке спаршеной страницы*/
        $this->stat['year'] = '20' . explode(' ', explode('.', $head_date)[2])[0];

        /**По принятой переменной $league определяем $time и $date. В завимости от них определяеv каким образом
        "разбирать" дату и время на странице матча/ У матчей разных чемпионатов разный формат разбора даты и времен.
         В зависимости от принимаемых констант от LeagueParseController устанавливаем переменные для парсинга в опред. лигах*/
        if ($league == 'spain'){
            $time = 4;
            $date = 3;
        }elseif ($league == 'england'){
            $time = 7;
            $date = 6;
        }elseif ($league == 'germany'){
            $time = 6;
            $date = 5;
        }

        $time = explode(' ', $head_date)[$time];
//        var_dump($time);
        $date = substr_replace((explode(' ', $head_date)[$date]), $this->stat['year'], 6);

        $this->stat['datetime'] = Yii::$app->formatter->asDatetime($date . $time, 'php:Y-m-d H:i:s');

        return $this->stat;
    }

    /** Парсим данные по ссылкам - статистика - Только для Англии
     * проходимся в цикле - по количеству ссылок
     * в туре и записываем в массив $stat вызывая $play->parseStat()
     * , применяя функцию trim() для обрезания ненужных пробелов и символов
     * !!!Метод только для одноразовой записи данных в бд! НЕ для постоянного фонового парсинга по CRON
     * Использовал action для заполнения базы в 26-ти первых турах чемпионата
     */
    public function parseEng($pq_m, $i)
    {

        $this->stat['h_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(1)')->text();
        /** Определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную
         * для базы данных*/
        $this->stat['league_id'] = $this->parseChamp($pq_m[$i]);
        $head_date = trim($pq_m[$i]->find('.live_body #game_events .block_header')->text(), "\x00..\x1F");
        /** Если в спаршенной строке 'date'- не дата, а 'Перенесен' - записываем в поле delay - 1 true, если дата, то 0 - false */
        $this->stat['delay'] = $this->stat['date'] == 'Перенесен' ? 1 : 0;

        /**  Формируем year разбиваем по пробелу, а и по точке и берём только год
         * $time снова обрезаем по пробелу и берём ту часть, где время
         * date снова обрезаем по пробелу и берём дату в формает "05.02.17" и заменяем
         * функцией substr_replace часть строки начиная с 6-ой позиции подставляя год в нужном формате
         * собираем из предыдущих кусков и форматируем $app->formatter->asDatetime ['datetime']
         * под нужный формат в бд в виде "2017-02-03 22:45" */
        $this->stat['year'] = '20' . explode(' ', explode('.', $head_date)[2])[0];
        /**
         * $time = explode(' ', $head_date)[7]; - для Англии
         */
        $time = explode(' ', $head_date)[7];
        /**
         * substr_replace((explode(' ', $head_date)[5]), $this->stat['year'], 6); - для Германии
         * substr_replace((explode(' ', $head_date)[6]), $this->stat['year'], 6); - для Англии
         */
        $date = substr_replace((explode(' ', $head_date)[6]), $this->stat['year'], 6);

        $this->stat['datetime'] = Yii::$app->formatter->asDatetime($date . $time, 'php:Y-m-d H:i:s');

        return $this->stat;

    }

    /** Парсим данные по ссылкам - статистика - Только для Германии
     * проходимся в цикле - по количеству ссылок
     * в туре и записываем в массив $stat вызывая $play->parseStat()
     * , применяя функцию trim() для обрезания ненужных пробелов и символов
     * !!!Метод только для одноразовой записи данных в бд! НЕ для постоянного фонового парсинга по CRON
     * Использовал action для заполнения базы в 22 первых турах чемпионата
     */
    public function parseGer($pq_m, $i)
    {

        $this->stat['h_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(1)')->text();
        /** Определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную
         * для базы данных*/
        $this->stat['league_id'] = $this->parseChamp($pq_m[$i]);
        $head_date = trim($pq_m[$i]->find('.live_body #game_events .block_header')->text(), "\x00..\x1F");
//        var_dump($head_date);

        $this->stat['delay'] = $this->stat['date'] == 'Перенесен' ? 1 : 0;

        /**  Формируем year разбиваем по пробелу, а и по точке и берём только год
         * $time снова обрезаем по пробелу и берём ту часть, где время
         * date снова обрезаем по пробелу и берём дату в формает "05.02.17" и заменяем
         * функцией substr_replace часть строки начиная с 6-ой позиции подставляя год в нужном формате
         * собираем из предыдущих кусков и форматируем $app->formatter->asDatetime ['datetime']
         * под нужный формат в бд в виде "2017-02-03 22:45" */
        $this->stat['year'] = '20' . explode(' ', explode('.', $head_date)[2])[0];
        /**
         * $time = explode(' ', $head_date)[7]; - для Англии
         */
        $time = explode(' ', $head_date)[6];
        /**
         * substr_replace((explode(' ', $head_date)[5]), $this->stat['year'], 6); - для Германии
         * substr_replace((explode(' ', $head_date)[6]), $this->stat['year'], 6); - для Англии
         */
        $date = substr_replace((explode(' ', $head_date)[5]), $this->stat['year'], 6);

        $this->stat['datetime'] = Yii::$app->formatter->asDatetime($date . $time, 'php:Y-m-d H:i:s');

        return $this->stat;

    }

//    /** Метод, который определяет является ли игра новой! Ищём ссылку на матч в бд! Если такой ссылки нет($is_link==null),
//    то возвращаем false*/
//    public static function isNewPlay($array)
//    {
//
//        $is_link = Play::find()->asArray()->andWhere(['link' => $array['link']])->one();
////        var_dump($array);
//
//        return $is_link ? false : true;
//
//    }
    /**Сравниваем спаршенные данные с теми, что уже есть в базе данных!
     * Создаём такое условие, при котором мы не запишем в бд одинаковых матчей
     * $array - принимаемый массив от контроллера со спаршенными данными. Записываем данные в бд*/
    public function playInsert($array)
    {

        foreach ($array as &$value) {

            /** Если игра перенесена, то тем значениям, что null присваиваем нули до тех пор пока игра не будет сыграна  */
            if ($value['delay'] == 1) {
                $value['h_tid_posses'] = 0;
                $value['a_tid_posses'] = 0;
                $value['h_tid_shot_on_goal'] = 0;
                $value['a_tid_shot_on_goal'] = 0;
                $value['h_tid_foul'] = 0;
                $value['a_tid_foul'] = 0;
                $value['h_tid_corner'] = 0;
                $value['a_tid_corner'] = 0;
                $value['h_tid_offside'] = 0;
                $value['a_tid_offside'] = 0;
                $value['h_tid_yellow_cart'] = 0;
                $value['a_tid_yellow_cart'] = 0;
                $value['h_tid_red_cart'] = 0;
                $value['a_tid_red_cart'] = 0;

                /** Записываем данные в любом случае. Здесь мы получаем массив, который необходимо поместить в бд,
                будь то перенесённые или сыгранный матч. Сюда попажают только сыгранные и не записанные в бд матчи*/
                $conn = Yii::$app->db;
                $conn->createCommand()->batchInsert('play', ['year', 'link', 'date', 'delay', 'league_id', 'home_team_id', 'away_team_id',
                    'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal',
                    'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside',
                    'h_tid_yellow_cart', 'a_tid_yellow_cart', 'h_tid_red_cart', 'a_tid_red_cart'],
                    [
                        [$value['year'], $value['link'], $value['datetime'], $value['delay'], $value['league_id'], $value['team_home'], $value['team_away'],
                            $value['team_home_score'], $value['team_away_score'], $value['h_tid_posses'], $value['a_tid_posses'],
                            $value['h_tid_shot_on_goal'], $value['a_tid_shot_on_goal'], $value['h_tid_foul'], $value['a_tid_foul'],
                            $value['h_tid_corner'], $value['a_tid_corner'], $value['h_tid_offside'], $value['a_tid_offside'],
                            $value['h_tid_yellow_cart'], $value['a_tid_yellow_cart'], $value['h_tid_red_cart'], $value['a_tid_red_cart']
                        ]
                    ])->execute();
            }
        }
        return $array;
    }

    /** Метод для записи в бд подготовленного массива
     * $value - подготовленный массив, передаваемый из playInsert()
     */
//    public function myBatchInsert($value)
//    {
//
//        $conn = Yii::$app->db;
//        $conn->createCommand()->batchInsert('play', ['year', 'link', 'date', 'delay', 'league_id', 'home_team_id', 'away_team_id',
//            'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses', 'h_tid_shot_on_goal', 'a_tid_shot_on_goal',
//            'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner', 'h_tid_offside', 'a_tid_offside',
//            'h_tid_yellow_cart', 'a_tid_yellow_cart', 'h_tid_red_cart', 'a_tid_red_cart'],
//            [
//                [$value['year'], $value['link'], $value['datetime'], $value['delay'], $value['league_id'], $value['team_home'], $value['team_away'],
//                    $value['team_home_score'], $value['team_away_score'], $value['h_tid_posses'], $value['a_tid_posses'],
//                    $value['h_tid_shot_on_goal'], $value['a_tid_shot_on_goal'], $value['h_tid_foul'], $value['a_tid_foul'],
//                    $value['h_tid_corner'], $value['a_tid_corner'], $value['h_tid_offside'], $value['a_tid_offside'],
//                    $value['h_tid_yellow_cart'], $value['a_tid_yellow_cart'], $value['h_tid_red_cart'], $value['a_tid_red_cart']
//                ]
//            ])->execute();
//    }


    /** Парсим таблицу бомбардиров и ассистентов на странице чемпионатов. $frame - константа,
     * в зависимости от которой будем парсить бомбардиров(если $frame = 0) и ассистентов (если $frame = 1)*/
    public static function scorers($score, $frame, $n, $y)
    {

        //Проходимся в цикле с шагом +2 (1, 3, 5..)для span, т.к. четные span выдают пустыне строки и записываем в массив имена игроков
        $i = 1;
        while ($i <= 24){
            self::$scorer[$i] =
                [$score->find('.page_main_content .comp_column .live_comptt_bd:eq(' . $frame . ') .comptt_table_bd .comptt_table_player span:eq(' . $i . ')')->text()];
            $i+=2;
        }

        //Затем меняя счётчик $j меняем данные для парсинга стат.показателей и дописываем в уже существующий массив с именами игроков
        self::$count = 0;
        foreach (self::$scorer as $k => $value) {

            $n =1;
            self::arrayPush($k, $score, $frame, $n, $y);

        }

        return self::$scorer;
    }

    /** Метод для дополенения массива из метода scorers
     * var $frame - если $frame = 0 - бомбардиры, если $frame = 1 - ассистенты
     * var $k - ключи массива $scorer
     * var $score - объект phpQuery, принимаемый из контроллера
     * $n - счётчик с которого мы начинаем отчёт кол-ва array_push.
     * $y - счётчик кол-ва раз сколько мы будем вызывать arrayPush. 3 - для бомбардиров(голы, голы с пенальти, кол-во сыгранных матчей),
     * 2 - для ассистентов (ассисты, кол-во сыгранных матчей)*/
    public static function arrayPush($k, $score, $frame, $n, $y = null)
    {
        if ($n <= $y){

            array_Push(self::$scorer[$k], $score->find('.page_main_content .comp_column .live_comptt_bd:eq(' . $frame . ') .comptt_table_bd .comptt_table_plitem:eq(' . self::$count . ')')->text());
            $n++;
            self::$count++;
            self::arrayPush($k, $score, $frame, $n, $y);
        }

        return self::$scorer;

    }

    //Метод для определения чемпионата! Для view Бомбардиров и Ассистентов
    public static function scorersChamp($id)
    {

        switch ($id){
            case 1:
                $champ = 'Испания';
                break;
            case 2:
                $champ = 'Англия';
                break;
            case 3:
                $champ = 'Германия';
                break;
        }

        return $champ;

    }

}