<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StatWidget;
use app\components\TableWidget;

$this->title = 'Footballstat';
//echo debug($goal);
//echo debug($play);
//echo debug($scorehome);
//echo debug($scoreaway);
//echo debug($score);

?>

<section xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row intro-tables">
            <?php
            /* Выводим поочерёдно TableWidget для 3-ех чемпионатов и каждый из них кешируем */
            $duration = 0;
            $dependency = [
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT MAX(created_at) FROM play',
            ];
//            if ($this->beginCache('table', ['duration' => $duration, 'dependency' => $dependency])) {
                TableWidget::begin(['champ' => 1]);
                TableWidget::end();
                TableWidget::begin(['champ' => 2]);
                TableWidget::end();
                TableWidget::begin(['champ' => 3]);
                TableWidget::end();
//                $this->endCache();
//            }
            ?>
        </div>
    </div>
</section>

<section id="last-games">
    <div class="container">
        <ul class="nav nav-tabs nav-justified text-center">
            <li class="active"><h5><a data-toggle="tab" href="#champ1">Испания</a></h5></li>
            <li><h5><a data-toggle="tab" href="#champ2">Англия</a></h5></li>
            <li><h5><a data-toggle="tab" href="#champ3">Германия</a></h5></li>
        </ul>
        <div class="tab-content">
            <div id="champ1" class="tab-pane in active">
                <h5>Испания</h5>
                <p>Содержимое 1 панели...</p>
            </div>
            <div id="champ2" class="tab-pane fade">
                <h5>Англия</h5>
                <p>Содержимое 2 панели...</p>
            </div>
            <div id="champ3" class="tab-pane fade">
                <h5>Германия</h5>
                <p>Содержимое 3 панели...</p>
            </div>
        </div>
    </div>
</section>

<section id="services" class="section section-padded">
    <div class="container">
        <div class="row text-center title">
            <h2 class="black">Статистические факты</h2>
            <h4 class="light muted">Самое интересное из Ведущих Европейских чемпионатов</h4>
        </div>
        <?php
            //Подключаем и кешируем виджет StatWidget
            //if ($this->beginCache('stat', ['duration' => $duration, 'dependency' => $dependency])) {
            StatWidget::begin(['champ' => '1']);
            StatWidget::end();
            StatWidget::begin(['champ' => '2']);
            StatWidget::end();
            StatWidget::begin(['champ' => '3']);
            StatWidget::end();
            //    $this->endCache();
            //}
        ?>
    </div>
</section>
