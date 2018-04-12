<?php
/**
 * @param $arr
 * Глобальная функция для распечатки объектов и массивы
 */
function debug($arr){
    echo '<pre>' . print_r($arr, true) . '<pre>';
}

/**
 * @param $url
 * @param string $referer
 * @return Глобальная функция для использования Curl при парсинге в ParseController
 */
function curl_get($url, $referer = 'http://www.google.com'){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/9.0 (Windows NT 6.1; WOW64;rv:38.0) Gecko/20100101 Firefox/38/0");
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;

}

function getDateTo_Search(){

    $year = date('Y');
    $date = $year . '-' . Yii::$app->params['dateEndSeason'];
    $date = new DateTime($date);

    $d1 = new DateTime('now');

    $a = date_diff($d1, $date);
//    echo $a->format('%R%a дней');


    $dateTo_Search = ($d1 < $date) ? ((int)$year - 1) . '-' . Yii::$app->params['dateEndSeason'] : $year . '-' . Yii::$app->params['dateEndSeason'];

    echo $dateTo_Search;

}
