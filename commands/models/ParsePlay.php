<?php

namespace app\commands\models;

use yii\base\Model;
use Yii;

class ParsePlay extends Model
{

    public $stat;
    public $href;
    public $team_home;
    public $team_away;


    /**
     * @param $team - объект phpQuery переданный из контреллера
     * @return int - возвращаю Лигу в виде целого числа
     * Метод для определения чемпионата! Ищем в спарсенной строке и в зависимости от неё
     * подготавливаем переменную для базы данных для записи матча
     */
    public static function parseChamp($team)
    {

        $champ = trim($team->find('.live_body #game_events .block_header')->text(), "\x00..\x1F");

        $champ = explode(', ', $champ)[0];

        if (preg_match('/Примера/', $champ)) {
            $league = 1;
        } elseif (preg_match('/Премьер лига/', $champ)) {
            $league = 2;
        } elseif (preg_match('/Бундеслига/', $champ)) {
            $league = 3;
        } elseif (preg_match('/Серия А/', $champ)) {
            $league = 4;
        }

        return $league;

    }

    /**
     * @param $team - объект phpQuery переданный из контреллера
     * @param $i - счётчик переходов по матчам по количеству игр в туре.10 - Испания, Англия, 9 - Германия
     * @param $res - Что парсим, зависит от передаваемой константы: 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад или
     * тур, который проходит в данных момент
     * @return mixed - возвращаем массив $this->stat[] c названием команд, счётом, ссылкой на подробную статистику, дату, год
     * Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка проходимся в цикле 10 раз - по количеству игр
     * в туре и записываем в массив $stat вызывая $play->parsePlay(), применяя функцию trim() для обрезания ненужных пробелов и символов
     * parseLive В зависимости от параметра $res парсит недавно сыгранные матчи которые находятся в классе
     * .block_header:eq(0) или сыгранные 2-3 назад, которые находятся в классе .block_header:eq(1) на странице рез-тов
     */
    public function parseLive($team, $i, $res)
    {

        $this->stat['tour'] = trim($team->find('.block_header:eq(' . $res . ')')->text(), "\x00..\x1F");

//        var_dump($this->stat['tour']);

        foreach ($team->find('.block_body_nopadding:eq(' . $res . ')') as $game) {

            $game = pq($game);

            $this->stat['link'] = $game->find('.game_block:eq(' . $i . ') a:eq(0)')->attr('href');
            $this->stat['date'] = trim($game->find('.game_block:eq(' . $i . ') .game_ht .game_start:eq(0)')->text(), "\x00..\x1F");

            $this->stat['year'] = explode('.', $this->stat['date'])[2];

            /** Обрезаем полученые результаты в виде строки - 2 символа в начале строки у 'team_home' */
            $this->stat['team_home'] = substr($game->find('.game_block:eq(' . $i . ') .game_ht:eq(0) .game_team:eq(0)')->text(), 2);
            $this->stat['team_home_score'] = trim($game->find('.game_block:eq(' . $i . ') .game_ht:eq(0) .game_goals:eq(0)')->text(), "\x00..\x1F");
            /** Обрезаем полученые результаты в виде строки - 2 символа в конце строки у 'team_away' */
            $this->stat['team_away'] = substr($game->find('.game_block:eq(' . $i . ') .game_at:eq(0) .game_team:eq(0)')->text(), 0, -2);
            $this->stat['team_away_score'] = trim($game->find('.game_block:eq(' . $i . ') .game_at:eq(0) .game_goals:eq(0)')->text(), "\x00..\x1F");

//            $this->stat['next'] = trim($team->find('.block_body_nopadding:eq(0) .game_block:eq(' . $i . ')')->next()->text(), "\x00..\x1F");

//            var_dump($this->stat);

        }

        return $this->stat;
    }

//    public function parseNext($team, $i){
//        $this->stat['next'] = trim($team->find('.block_body_nopadding:eq(0) .game_block:eq(' . $i . ')')->next()->text(), "\x00..\x1F");
//
////        var_dump($this->stat['next']);
//        return $this->stat['next'];
//    }


    /**
     * @param $team - объект phpQuery переданный из контреллера
     * @param $i - счётчик переходов по матчам по количеству игр в туре
     * @param $league - В завимости от неё определяем каким образом "разбираем" дату и время на странице матча
     * У матчей чемпионата Испании один формат, у остальных(Англии и Германии) другой
     * @return mixed - возвращаем массив $this->stat
     * Парсим данные по ссылкам - статистика! проходимся в цикле - по количеству ссылок
     * в туре и записываем в массив $stat вызывая $play->parseStat(),применяя функцию trim() для обрезания ненужных
     * пробелов и символов. Используется только для парсинга статистики.
     * По принятым переменным $time_stat и $date_stat определяем каким образом разобрать дату и время.
     */
    public function parseStat($team, $i)
    {

//        var_dump($team);

        $this->stat['h_tid_posses'] = $team[$i]->find('.stats_item:eq(4) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_posses'] = $team[$i]->find('.stats_item:eq(4) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_shot_on_goal'] = $team[$i]->find('.stats_item:eq(1) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_shot_on_goal'] = $team[$i]->find('.stats_item:eq(1) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_foul'] = $team[$i]->find('.stats_item:eq(6) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_foul'] = $team[$i]->find('.stats_item:eq(6) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_corner'] = $team[$i]->find('.stats_item:eq(5) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_corner'] = $team[$i]->find('.stats_item:eq(5) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_offside'] = $team[$i]->find('.stats_item:eq(7) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_offside'] = $team[$i]->find('.stats_item:eq(7) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_yellow_cart'] = $team[$i]->find('.stats_item:eq(8) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_yellow_cart'] = $team[$i]->find('.stats_item:eq(8) .stats_inf:eq(1)')->text();
        $this->stat['h_tid_red_cart'] = $team[$i]->find('.stats_item:eq(9) .stats_inf:eq(0)')->text();
        $this->stat['a_tid_red_cart'] = $team[$i]->find('.stats_item:eq(9) .stats_inf:eq(1)')->text();
        /* Определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную для базы данных*/
        $this->stat['league_id'] = self::parseChamp($team[$i]);
        $head_date = trim($team[$i]->find('.live_body #game_events .block_header')->text(), "\x00..\x1F");
        /*Если в спаршенной строке 'date'- не дата, а 'Перенесен' - записываем в поле delay - 1 true, если дата, то 0 - false */
        $this->stat['delay'] = $this->stat['date'] == 'Перенесен' ? 1 : 0;
        /* Формируем year разбиваем по пробелу, а и по точке и берём только год
         * $time снова обрезаем по пробелу и берём ту часть, где время
         * date снова обрезаем по пробелу и берём дату в формает "05.02.17" и заменяем
         * функцией substr_replace часть строки начиная с 6-ой позиции подставляя год в нужном формате
         * собираем из предыдущих кусков и форматируем $app->formatter->asDatetime ['datetime']
         * под нужный формат в бд в виде "03.02.2017 22:45"*/
        $this->stat['year'] = '20' . explode(' ', explode('.', $head_date)[2])[0];

//        $time = explode(' ', $head_date)[$time];
        $date = trim(substr($head_date, -14, 9));
        $time = trim(substr($head_date, -5, 5));

//        var_dump($head_date, $date, $time);die();

//        var_dump($date);
        //Превращаем дату из 18.03.18 в 18.03.2018
        $date = substr_replace($date, $this->stat['year'], 6);
//        var_dump($date);die();

        $this->stat['datetime'] = Yii::$app->formatter->asDatetime($date . $time, 'php:Y-m-d H:i');

//        var_dump($this->stat);

        return $this->stat;
    }


    /**
     * @param $array - принимаемый массив от контроллера со спаршенными данными. Записываем данные в бд
     * @return mixed - возвращаем массив, который мы записали в бд. Возвращаем только для отладки, т.к метод для
     * консольного контроллера
     * Метод для записи матча в бд. Если игра перенесена($value['delay'] == 1), то показатели заполняем 0
     */
    public function playInsert($array)
    {

 //var_dump($array);

        foreach ($array as &$value) {

            /* Если игра перенесена, то тем значениям, что null присваиваем нули до тех пор пока игра не будет сыграна */
            if ($value['date'] == 'Перенесен') {
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
            }

            /*Записываем данные в любом случае. Здесь мы получаем массив, который необходимо поместить в бд,
            будь то перенесённые или сыгранный матч. Сюда попадают только сыгранные и не записанные в бд матчи*/
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
        return $array;
    }
}