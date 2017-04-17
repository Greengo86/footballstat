<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\tabs\TabsX;
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

<?php
    $items = [
        [
            'label'=>'<i class="glyphicon glyphicon-home"></i> Испания',
            'content'=>$play,
            'active'=>true,
            'linkOptions'=>['data-url'=>Url::to(['/play/tabs-data'])]
        ],
        [
            'label'=>'<i class="glyphicon glyphicon-user"></i> Англия',
            'content'=>$play,
            'linkOptions'=>['data-url'=>Url::to(['/play/tabs-data'])]
        ],
        [
            'label'=>'<i class="glyphicon glyphicon-user"></i> Германия',
            'content'=>$play,
            'linkOptions'=>['data-url'=>Url::to(['/play/tabs-data'])]
        ],
    ];
?>

<section id="last-games">
    <div class="container">
        <ul class="nav nav-tabs nav-justified text-center">
            <?php
                echo TabsX::widget([
                'items'=>$items,
                'position'=>TabsX::POS_ABOVE,
                'align'=>TabsX::ALIGN_CENTER,
                'encodeLabels'=>false
                ]);
            ?>
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
