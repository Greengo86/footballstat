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

<section id="tables">
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
        <div class="row text-center title">
            <h2 class="black">Последние матчи</h2>
        </div>
        <?php

        $opt = [
            [
                'label'=>'<img src="/web/img/champ/1.png"> Испания',
                'active'=>true,
                'linkOptions'=>['data-url'=>Url::to(['play/last-games/1'])]
            ],
            [
                'label'=>'<img src="/web/img/champ/2.png"> Англия',
                'linkOptions'=>['data-url'=>Url::to(['play/last-games/2'])]
            ],
            [
                'label'=>'<img src="/web/img/champ/3.png"> Германия',
                'linkOptions'=>['data-url'=>Url::to(['play/last-games/3'])]
            ],
        ];

        echo TabsX::widget([
            'items'=>$opt,
            'position'=>TabsX::POS_ABOVE,
            'align'=>TabsX::ALIGN_CENTER,
            'encodeLabels'=>false
        ]);
        ?>
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
