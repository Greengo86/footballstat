<?php

use kartik\tabs\TabsX;
use yii\helpers\Url;

$opt = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Испания',
        'active'=>true,
        'content'=>$cont,
        'linkOptions'=>['data-url'=>Url::to(['/play/tabs-data'])]
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Англия',
        'content'=>$cont,
        'linkOptions'=>['data-url'=>Url::to(['/play/tabs-data'])]
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Германия',
        'content'=>$cont,
        'linkOptions'=>['data-url'=>Url::to(['/play/tabs-data'])]
    ],
];

echo TabsX::widget([
    'items'=>$opt,
    'position'=>TabsX::POS_ABOVE,
    'align'=>TabsX::ALIGN_CENTER,
    'encodeLabels'=>true
]);