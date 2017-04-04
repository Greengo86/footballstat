<?php

namespace app\models;


use yii\base\Model;


class Parse extends Model
{

    public static $scorer;
    public static $count;

    /**
     * @param $score - объект phpQuery, принимаемый из контроллера
     * @param $frame - если $frame = 0 - бомбардиры, если $frame = 1 - ассистенты
     * @param $n - счётчик с которого мы начинаем отчёт кол-ва array_push.
     * @param $y - счётчик кол-ва раз сколько мы будем вызывать arrayPush. 3 - для бомбардиров(голы, голы с пенальти, кол-во сыгранных матчей),
     * 2 - для ассистентов (ассисты, кол-во сыгранных матчей)
     * @return mixed - подготовленный массив self::$scorer[]
     * Парсим таблицу бомбардиров и ассистентов на странице чемпионатов. $frame - константа,
     * в зависимости от которой будем парсить бомбардиров(если $frame = 0) и ассистентов (если $frame = 1)
     */
    public static function scorers($score, $frame, $n, $y)
    {

        //Проходимся в цикле с шагом +2 (1, 3, 5..)для span, т.к. четные span выдают пустыне строки и записываем в массив имена игроков
        $i = 1;
        while ($i <= 24) {
            self::$scorer[$i] =
                [$score->find('.page_main_content .comp_column .live_comptt_bd:eq(' . $frame . ') .comptt_table_bd .comptt_table_player span:eq(' . $i . ')')->text()];
            $i += 2;
        }

        //Затем меняя счётчик $j меняем данные для парсинга стат.показателей и дописываем в уже существующий массив с именами игроков
        self::$count = 0;
        foreach (self::$scorer as $k => $value) {

            $n = 1;
            self::arrayPush($k, $score, $frame, $n, $y);

        }

        return self::$scorer;
    }

    /**
     * @param $score - объект phpQuery, принимаемый из контроллера
     * @param $k - ключ массива $scorer из метода scorers! span'ы 1, 3, 5, 7..  которые спарсили со страницы бомбардиров и ассистентов!
     * Значениями этих ключей являются фамилии игроков
     * @param $frame - если $frame = 0 - бомбардиры, если $frame = 1 - ассистенты
     * @param $n - счётчик с которого мы начинаем отчёт кол-ва array_push.
     * @param $y - счётчик кол-ва раз сколько мы будем вызывать arrayPush. 3 - для бомбардиров(голы, голы с пенальти,
     * кол-во сыгранных матчей),2 - для ассистентов (ассисты, кол-во сыгранных матчей!
     * @return mixed - подготовленный массив self::$scorer[] для вывода во view! Бомбардиры и ассисенты
     * Метод для дополенения массива из метода scorers для вывода во views бомбардиров и ассистентов*/
    public static function arrayPush($k, $score, $frame, $n, $y = null)
    {
        if ($n <= $y) {

            array_Push(self::$scorer[$k], $score->find('.page_main_content .comp_column .live_comptt_bd:eq(' . $frame . ') .comptt_table_bd .comptt_table_plitem:eq(' . self::$count . ')')->text());
            $n++;
            self::$count++;
            self::arrayPush($k, $score, $frame, $n, $y);
        }

        return self::$scorer;

    }

    /**
     * @param $id - id чеимпионата
     * @return string - возвращаем строку с названием чемпионата
     * Метод для определения чемпионата! Для view Бомбардиров и Ассистентов
     */
    public static function scorersChamp($id)
    {

        switch ($id) {
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