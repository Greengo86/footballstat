<?php
/**Парсим результаты матчей и статистику с http://soccer365.ru */

namespace app\commands\controllers;

use app\commands\events\PlayInsertEvent;
use app\commands\models\ParsePlay;
use Yii;


use app\models\Team;
use app\models\Play;
use yii\console\Controller;


class ParseController extends Controller
{

    /** Основной url сайта с которого будем парсить*/
    const DOMEN = 'http://soccer365.ru';
    //Устанавливаем константу для консольного приложения(парсинга мачтей), как признак, что игра добавлена в бд
    const RECORD_INSERTED = 'RECORD_INSERTED';


    public $stat = [];
    public $to_record = [];


    /**
     * @param $url
     * @param $k
     * @param $res
     * @param $league
     * @return array
     * Экшен для ручной записи в базу данных матчей, которые уже прошли довольно давно! 2-3 недели назад. А именно для парсинга
     * со страницы "резульататы" - http://soccer365-1.xyz/competitions/16/results/ - Здесь, например, Испания!
     */
    public function actionOld($url, $k, $res)
    {

        /** Эмулируем работу браузера с помощью curl*/
        $dom = \curl_get($url);

        /** Создаем объект phpQuery */
        $team = \phpQuery::newDocumentHTML($dom);
        $model = new ParsePlay();
        $i = 0;

        /** Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка!проходимся в цикле 9 или 10 раз - по количеству игр
         * в туре и записываем в массив $stat вызывая $play->parsePlay()*/
        while ($i <= $k) {

            $this->stat[$i] = $model->parseLive($team, $i, 0);
            /** Если в массиве $this->stat['link'], есть ссылка, что и в базе данных,
             * то берём ссылки на матчи и проходимся по ним по очереди, парся статистику.Если там присутствует
             * слово 'live' то также этот матч не записываем в бд до его окончания */
            $is_link = Play::find()->asArray()->andWhere(['link' => $this->stat[$i]['link']])->one();
            if ($is_link !== null || strpos($this->stat[$i]['link'], 'live') == true) {
                return null;
            }

//            var_dump($this->stat);

//            if($this->stat[$i]['league_id'] !== null  && $this->stat[$i]['datetime'] !== '1970-01-01 03:00'){
//
//            }
            foreach ($this->stat as $link) {
//                var_dump($this->stat);
                /** "Склеиваем" домен(главная страница сайта) и ссылку на каждый отдельный матч */
                $dom1[$i] = self::DOMEN . $link['link'];
                /** Эмулируем работу браузера с помощью curl*/
                $game[$i] = curl_get($dom1[$i]);
                /** Создаем объект phpQuery */
                $match[$i] = \phpQuery::newDocumentHTML($game[$i]);
                $pq_m[$i] = pq($match[$i]);
                /** Парсим данные по ссылкам - статистика! проходимся в цикле - по количеству ссылок
                 * в туре и записываем в массив $stat[$i]*/
                /** В массив $this->to_record записываем полные стат данные, которых ещё нет в бд  */
                $this->to_record[$i] = $model->parseStat($pq_m, $i);
                /** Берём спаршенные названия команд в виде строк, запрашиваем связанные данные из таблицы Team
                 * и дозаписываем в $this->to_record, для того, чтобы записать в базу данных (int) id команд,
                 * учавствующей в матче номер названия команды и id лиги для записи в базу данных*/
                $team_home[$i] = Team::find()->andWhere(['team_name' => $link['team_home']])->one();
                $this->to_record[$i]['team_home'] = $team_home[$i]->team_id;
                $team_away[$i] = Team::find()->andWhere(['team_name' => $link['team_away']])->one();
                $this->to_record[$i]['team_away'] = $team_away[$i]->team_id;
            }
            $i++;
        }

        \phpQuery::unloadDocuments($team);
        /** вызываем метод, который запишет подготовленные данные в бд */
        $model->playInsert($this->to_record);

        return $this->to_record;
    }


    /** Экшен для парсинга матчей, которые завершились 2-3 назад - класс .block_body_nopadding:eq(1) - метод уже не используется */
    public function actionChamp($url)
    {

        /** Эмулируем работу браузера с помощью curl*/
        $dom = curl_get($url);

        /** Создаем объект phpQuery */
        $team = \phpQuery::newDocumentHTML($dom);

        $model = new Parse();

        $i = 0;

        /** Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка! проходимся в цикле 10 раз - по количеству игр
         * в туре и записываем в массив $stat вызывая $play->parsePlay()
         */
        while ($i <= 3) {
            $next_game = $team->find('.block_body_nopadding:eq(1) .game_block:eq(' . $i . ')')->html();
            if (!empty($next_game)) {
                $this->stat[$i] = $model->parsePlay($team, $i);

                /** Из предыдущего массива берём ссылкы матчи и проходимся по ним по очереди, парся статистику  */
                foreach ($this->stat as $link) {
                    if (is_array($this->stat) && !null == $link) {
                        /** Склеиваем домен(главная страница сайта) и ссылку на каждый отдельный матч */
//                        var_dump($link['href'][$i]);
                        $dom1[$i] = self::DOMEN . $link['href'];
                        /** Эмулируем работу браузера с помощью curl*/
                        $game[$i] = curl_get($dom1[$i]);
                        /** Создаем объект phpQuery */
                        $match[$i] = \phpQuery::newDocumentHTML($game[$i]);
                        $pq_m[$i] = pq($match[$i]);
                        /** Парсим данные по ссылкам - статистика!
                         * проходимся в цикле - по количеству ссылок
                         * в туре и записываем в массив $stat[$i] */
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
                        /**  Формируем $datetime(Обрезаем по пробелу и берём только время) и форматируем под нужный формат в бд */
                        $time = explode(' ', $head_date)[4];
                        $datetime = $this->stat[$i]['date'] . ' ' . $time;
                        $this->stat[$i]['datetime'] = Yii::$app->formatter->asDatetime($datetime, 'php:Y-m-d H:i:s');

                        /** Берём спаршенные названия команд в виде строк, запрашиваем связанные данные из таблицы Team
                         * и записываем в this->stat[$i], для того, чтобы записать в базу данных (int) id команд,
                         * учавствующей в матче номер названия команды и id лиги для записи в базу данных*/
                        $team_home[$i] = Team::find()->andWhere(['team_name' => $link['team_home']])->one();
                        $this->stat[$i]['team_home'] = $team_home[$i]->team_id;
                        $team_away[$i] = Team::find()->andWhere(['team_name' => $link['team_away']])->one();
                        $this->stat[$i]['team_away'] = $team_away[$i]->team_id;
                        /** Определения чемпионата! Ищем в спарсенной строке и в зависимости от неё подготавливаем переменную
                         * для базы данных*/
                        $this->stat[$i]['league_id'] = parse::parseChamp($team);
                    }
                }
                $i++;
            }
        }

        /**
         * Сравниваем спаршенные данные с теми, что уже есть в базе данных!
         * Создаём такое условие, при котором мы не запишем в бд одинаковых матчей
         */
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


    /**
     * @param $url - принимаем ссылку от дочернего контроллера, с которой будем парсить данные
     * @param $k - счётчик переходов по матчам по количеству игр в туре! отсчёт начиная с 0 - 10 игр
     * @param $res - принимаемая константа LIVE или PLAY, в зависимости от которой : 0 - недавно сыгранные матчи, 1 - матчи, сыгранные 2-3 назад
     * @param $league - принимаем строку в завимости от которой модель определит каким образом "разбирать" дату и время на странице матча
     * У матчей разных чемпионатов разные формата разбора
     * @return array - возвращаем массив, который будет записан в бд. Возвращаем только для тестирования
     * Экшен для парсинга матчей!
     */
    public function actionLive($url, $k, $res)
    {

        /** Эмулируем работу браузера с помощью curl*/
        $dom = \curl_get($url);

        /** Создаем объект phpQuery */
        $team = \phpQuery::newDocumentHTML($dom);
        $model = new ParsePlay();
        $i = 0;

        /** Парсим данные с сайта - тур, названия команд, счёт, дата и ссылка!проходимся в цикле 9 или 10 раз - по количеству игр
         * в туре и записываем в массив $stat вызывая $play->parsePlay()*/

//        $this->stat[$i]['next'] = 'StartParse';

        while ($i <= $k) {

            $this->stat[$i] = $model->parseLive($team, $i, 0);

            /** Если в массиве $this->stat['link'], есть ссылка, что и в базе данных,
             * то берём ссылки на матчи и проходимся по ним по очереди, парся статистику.Если там присутствует
             * слово 'live' то также этот матч не записываем в бд до его окончания */
//            var_dump($this->stat[$i]);
            $is_link = Play::find()->asArray()->andWhere(['link' => $this->stat[$i]['link']])->one();

            if ($is_link !== null && $this->stat[$i]['date'] !== 'Перенесен'){
                $i++;
                continue;
            } elseif(strpos($this->stat[$i]['link'], 'live') == true && $this->stat[$i]['date'] !== 'Перенесен'){
                $i++;
                continue;
            } else/*if($is_link == null && $this->stat[$i]['date'] == 'Перенесен')*/{
//                Записываем в базу
                foreach ($this->stat as $link) {
                    /** "Склеиваем" домен(главная страница сайта) и ссылку на каждый отдельный матч */
                    $dom1[$i] = self::DOMEN . $link['link'];
                    /** Эмулируем работу браузера с помощью curl*/
                    $game[$i] = curl_get($dom1[$i]);
                    /** Создаем объект phpQuery */
                    $match[$i] = \phpQuery::newDocumentHTML($game[$i]);
                    $pq_m[$i] = pq($match[$i]);
                    /** Парсим данные по ссылкам - статистика! проходимся в цикле - по количеству ссылок
                     * в туре и записываем в массив $stat[$i]*/
                    /** В массив $this->to_record записываем полные стат данные, которых ещё нет в бд  */
                    $this->to_record[$i] = $model->parseStat($pq_m, $i);
                    if($this->to_record[$i]['league_id'] == null && $this->to_record[$i]['datetime'] == '1970-01-01 03:00'){
                        unset($this->to_record[$i]);
                        $i++;
                        continue;
                    }
                    /** Берём спаршенные названия команд в виде строк, запрашиваем связанные данные из таблицы Team
                     * и дозаписываем в $this->to_record, для того, чтобы записать в базу данных (int) id команд,
                     * учавствующей в матче номер названия команды и id лиги для записи в базу данных*/
                    $team_home[$i] = Team::find()->andWhere(['team_name' => $link['team_home']])->one();
                    $this->to_record[$i]['team_home'] = $team_home[$i]->team_id;
                    $team_away[$i] = Team::find()->andWhere(['team_name' => $link['team_away']])->one();
                    $this->to_record[$i]['team_away'] = $team_away[$i]->team_id;
//                    var_dump($this->to_record[$i]);
                }
            }

//            $this->stat[$i]['next'] = $model->parseNext($team, $i);
            $i++;

        }
//        var_dump($this->to_record);

        \phpQuery::unloadDocuments($team);
        /* вызываем метод, который запишет подготовленные данные в бд */
        $model->playInsert($this->to_record);

        $play = new Play();

        /*Если свойство $this->to_record не пусто, считаем что произошла запись данных в бд и инициируем событие,
        которое отправить сообщение на электронную почту с соответствующими сведениями*/
        if (!empty($this->to_record)) {

            //Создаем объект, который будет являться экземпляром класса yii\base\Event для того, чтобы передать
            // его в качестве второго параметра методу yii\base\Component::trigger() для более полной информации о записи в бд
            $event = new PlayInsertEvent();
            $event->play_insert = $this->to_record;
            //Инициируем событие 'запись вставлена' с помощью yii\base\Component::trigger() в моделе play
            $play->trigger(Play::RECORD_INSERTED, $event);
        }

        return $this->to_record;
    }

}