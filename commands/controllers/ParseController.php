<?php
/*Парсим результаты матчей и статистику с http://soccer365.ru */

namespace app\commands\controllers;

use Yii;
use app\models\Parse;
use app\models\Team;
use app\models\Play;
use yii\console\Controller;


class ParseController extends Controller
{

    /* Основной url сайта с которого будем парсить*/
    const DOMEN = 'http://soccer365.ru';


    public $stat = [];
    public $to_record = [];


    /* Экшен для парсинга матчей, которые завершились 2-3 назад - класс .block_body_nopadding:eq(1) */
    public function actionChamp($url)
    {

        /* Эмулируем работу браузера с помощью curl*/
        $dom = curl_get($url);

        /* Создаем объект phpQuery */
        $team = \phpQuery::newDocumentHTML($dom);

        $model = new Parse();

        $i = 0;

        /* Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка! проходимся в цикле 10 раз - по количеству игр
        в туре и записываем в массив $stat вызывая $play->parsePlay()*/
        while ($i <= 3){
            $next_game = $team->find('.block_body_nopadding:eq(1) .game_block:eq(' . $i . ')')->html();
            if( !empty($next_game) ){
                $this->stat[$i] = $model->parsePlay($team, $i);

                /*Из предыдущего массива берём ссылкы матчи и проходимся по ним по очереди, парся статистику */
                foreach ($this->stat as $link){
                    if(is_array($this->stat) && !null == $link){
                        /*Склеиваем домен(главная страница сайта) и ссылку на каждый отдельный матч */
//                        var_dump($link['href'][$i]);
                        $dom1[$i] = self::DOMEN . $link['href'];
                        /*Эмулируем работу браузера с помощью curl*/
                        $game[$i] = curl_get($dom1[$i]);
                        /*Создаем объект phpQuery */
                        $match[$i] = \phpQuery::newDocumentHTML($game[$i]);
                        $pq_m[$i] = pq($match[$i]);
                        /*Парсим данные по ссылкам - статистика!
                        проходимся в цикле - по количеству ссылок
                        в туре и записываем в массив $stat[$i] */
//                        $this->stat[$i]['home_score_1st'] = trim($pq_m[$i]->find('.live_body .event_ht .icon_video16 has-tip tooltipstered')->text(), "\x00..\x1F");
//                        var_dump($this->stat[$i]['home_score_1st']);
                        $this->stat[$i]['h_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_posses'] = $pq_m[$i]->find('.stats_item:eq(3) .stats_inf:eq(1)')->text();
                        $this->stat[$i]['h_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_shot_on_goal'] = $pq_m[$i]->find('.stats_item:eq(1) .stats_inf:eq(1)')->text();
                        $this->stat[$i]['h_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_foul'] = $pq_m[$i]->find('.stats_item:eq(5) .stats_inf:eq(1)')->text();
                        $this->stat[$i]['h_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_corner'] = $pq_m[$i]->find('.stats_item:eq(4) .stats_inf:eq(1)')->text();
                        $this->stat[$i]['h_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_offside'] = $pq_m[$i]->find('.stats_item:eq(6) .stats_inf:eq(1)')->text();
                        $this->stat[$i]['h_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_yellow_cart'] = $pq_m[$i]->find('.stats_item:eq(7) .stats_inf:eq(1)')->text();
                        $this->stat[$i]['h_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(0)')->text();
                        $this->stat[$i]['a_tid_red_cart'] = $pq_m[$i]->find('.stats_item:eq(8) .stats_inf:eq(1)')->text();
                        $head_date = $pq_m[$i]->find('.live_body #game_events .block_header')->text();
                        /*Формируем $datetime(Обрезаем по пробелу и берём только время) и форматируем под нужный формат в бд */
                        $time = explode(' ', $head_date)[4];
                        $datetime = $this->stat[$i]['date'] . ' ' . $time;
                        $this->stat[$i]['datetime'] = Yii::$app->formatter->asDatetime($datetime, 'php:Y-m-d H:i:s');

                        /*Берём спаршенные названия команд в виде строк, запрашиваем связанные данные из таблицы Team
                        и записываем в this->stat[$i], для того, чтобы записать в базу данных (int) id команд,
                        учавствующей в матче номер названия команды и id лиги для записи в базу данных*/
                        $team_home[$i] = Team::find()->andWhere(['team_name' => $link['team_home']])->one();
                        $this->stat[$i]['team_home'] = $team_home[$i]->team_id;
                        $team_away[$i] = Team::find()->andWhere(['team_name' => $link['team_away']])->one();
                        $this->stat[$i]['team_away'] = $team_away[$i]->team_id;
                        /*Определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную
                         для базы данных*/
                        $this->stat[$i]['league_id'] = parse::parseChamp($team);
                    }
                }
                $i++;
            }
        }


        /*Сравниваем спаршенные данные с теми, что уже есть в базе данных!
        Создаём такое условие, при котором мы не запишем в бд одинаковых матчей*/
//        foreach ($this->stat as $value){
//        $date_comp = Play::find()->andWhere(['date' => $value['datetime'], 'home_team_id' => $value['team_home'], 'away_team_id' => $value['team_away']])->one();
//            if(empty($date_comp)){
//
//                $conn = Yii::$app->db;
//                $conn->createCommand()->batchInsert('play', ['year', 'date', 'league_id', 'home_team_id', 'away_team_id',
//                'home_score_1st', 'away_score_1st', 'home_score_full', 'away_score_full', 'h_tid_posses', 'a_tid_posses',
//                'h_tid_shot_on_goal', 'a_tid_shot_on_goal', 'h_tid_foul', 'a_tid_foul', 'h_tid_corner', 'a_tid_corner',
//                'h_tid_offside', 'a_tid_offside', 'h_tid_yellow_cart', 'a_tid_yellow_cart',
//                'h_tid_red_cart', 'a_tid_red_cart'], [
//                    [$value['year'], $value['datetime'], $value['league_id'], $value['team_home'], $value['team_away'],
//                    0, 0, $value['team_home_score'], $value['team_away_score'], $value['h_tid_posses'], $value['a_tid_posses'],
//                    $value['h_tid_shot_on_goal'], $value['a_tid_shot_on_goal'], $value['h_tid_foul'], $value['a_tid_foul'],
//                    $value['h_tid_corner'], $value['a_tid_corner'], $value['h_tid_offside'], $value['a_tid_offside'],
//                    $value['h_tid_yellow_cart'], $value['a_tid_yellow_cart'], $value['h_tid_red_cart'], $value['h_tid_red_cart']
//                    ]
//                ])->execute();
//                echo 'Игра записана в базу данных' . '<br>';
//            }else echo 'Что-то пошло не так' . '<br>';
//        }
        return $this->stat;

    }

    /*Экшен для парсинга матчей! Класс .block_body_nopadding:eq($res) зависит от принимаемой переменной $res
    0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 дня назад. По принятой переменной $league определяем $time и $date.
    В завимости от них модель определяет каким образом "разбирать" дату и время на странице матча
    У матчей разных чемпионатов разные параметры парсинга времени и даты! Значения $time_stat, $date_stat -
    для каждого чемпионата будут свои*/
    public function actionLive($url, $k, $res, $league)
    {

        /*Эмулируем работу браузера с помощью curl*/
        $dom = \curl_get($url);

        /* Создаем объект phpQuery */
        $team = \phpQuery::newDocumentHTML($dom);
        $model = new Parse();
        $i = 0;

        /* Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка!проходимся в цикле 9 или 10 раз - по количеству игр
         в туре и записываем в массив $stat вызывая $play->parsePlay()*/
        while ($i <= $k){

            $this->stat[$i] = $model->parseLive($team, $i, $res);
            /* Если в массиве $this->stat['link'], есть ссылка, что и в базе данных,
            то берём ссылки на матчи и проходимся по ним по очереди, парся статистику.Если там присутствует
            слово 'live' то также этот матч не записываем в бд до его окончания */
            $is_link = Play::find()->asArray()->andWhere(['link' => $this->stat[$i]['link']])->one();
            if($is_link !== null || strpos($this->stat[$i]['link'], 'live') == true){
                $i++;
                continue;
            }
            foreach ($this->stat as $link){
                /* "Склеиваем" домен(главная страница сайта) и ссылку на каждый отдельный матч */
                $dom1[$i] = self::DOMEN . $link['link'];
                /* Эмулируем работу браузера с помощью curl*/
                $game[$i] = curl_get($dom1[$i]);
                /* Создаем объект phpQuery */
                $match[$i] = \phpQuery::newDocumentHTML($game[$i]);
                $pq_m[$i] = pq($match[$i]);
                /* Парсим данные по ссылкам - статистика! проходимся в цикле - по количеству ссылок
                в туре и записываем в массив $stat[$i]*/
                /* В массив $this->to_record записываем полные стат данные, которых ещё нет в бд  */
                $this->to_record[$i] = $model->parseStat($pq_m, $i, $league);
                /* Берём спаршенные названия команд в виде строк, запрашиваем связанные данные из таблицы Team
                и дозаписываем в $this->to_record, для того, чтобы записать в базу данных (int) id команд,
                учавствующей в матче номер названия команды и id лиги для записи в базу данных*/
                $team_home[$i] = Team::find()->andWhere(['team_name' => $link['team_home']])->one();
                $this->to_record[$i]['team_home'] = $team_home[$i]->team_id;
                $team_away[$i] = Team::find()->andWhere(['team_name' => $link['team_away']])->one();
                $this->to_record[$i]['team_away'] = $team_away[$i]->team_id;

            }
            $i++;
        }
        /* вызываем метод, который запишет подготовленные данные в бд */
        $model->playInsert($this->to_record);

        return $this->to_record;
//            /** Если в массиве $this->stat в значении 'link', есть ссылка, что и в базе данных,
//            то берём ссылки на матчи и проходимся по ним по очереди, парся статистику.Если там присутствует
//            слово 'live' то также этот матч не записываем в бд до его окончания */
//            foreach ($this->stat as $link){
//                if(true == parse::isNewPlay($link)){
//                    if(is_array($this->stat) && strpos($link['link'], 'live') == false){
//                        /** "Склеиваем" домен(главная страница сайта) и ссылку на каждый отдельный матч */
//                        $dom1[$i] = self::DOMEN . $link['link'];
//                        /** Эмулируем работу браузера с помощью curl*/
//                        $game[$i] = curl_get($dom1[$i]);
//                        /** Создаем объект phpQuery */
//                        $match[$i] = \phpQuery::newDocumentHTML($game[$i]);
//                        $pq_m[$i] = pq($match[$i]);
//                        /** Парсим данные по ссылкам - статистика! проходимся в цикле - по количеству ссылок
//                         * в туре и записываем в массив $stat[$i]. По принятой переменной $league определяем $time и $date.
//                        В завимости от них модель определяет каким образом "разбирать" дату и время в странице матча
//                        У матчей разных чемпионатов разный формат разбора даты и времени. В зависимости от принимаемых констант
//                        от дочернего контроллера устанавливаем переменные для парсинга*/
//                        if ($league == 'spain'){
//                            $time = 4;
//                            $date = 3;
//                        }elseif ($league == 'england'){
//                            $time = 7;
//                            $date = 6;
//                        }elseif ($league == 'germany'){
//                            $time = 6;
//                            $date = 5;
//                        }
//                        $this->stat[$i] = $model->parseStat($pq_m, $i, $time, $date);
//                        /** Берём спаршенные названия команд в виде строк, запрашиваем связанные данные из таблицы Team
//                        и записываем в this->stat[$i], для того, чтобы записать в базу данных (int) id команд,
//                         * учавствующей в матче номер названия команды и id лиги для записи в базу данных*/
//                        $team_home[$i] = Team::find()->andWhere(['team_name' => $link['team_home']])->one();
//                        $this->stat[$i]['team_home'] = $team_home[$i]->team_id;
//                        $team_away[$i] = Team::find()->andWhere(['team_name' => $link['team_away']])->one();
//                        $this->stat[$i]['team_away'] = $team_away[$i]->team_id;
//                        }
//                    }
//                }
//            $i++;
//        }
//        /** вызываем метод, который запишет нужные данные в бд */
//        $this->stat = $model->playInsert($this->stat);
//
//        return $this->stat;

    }

}