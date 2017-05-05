<?php

/* @var $this \yii\web\View */
/* @var $content string */

use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use app\models\Team;
use kartik\nav\NavX;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
<div class="preloader">
    <img src="/img/loader.gif" alt="Preloader image">
</div>

    <div class="wrapper">
        <div class="navbar yamm navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <a class="navbar-brand black fa fa-futbol-o fa-spin fa-2x" href="<?= Url::to(['/']) ?>"></a>
                
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<!--                Навигационный виджет NavX - лиги и команды-->
                    <?php echo NavX::widget(
                    [
                        'activateParents' => true,
                        'activateItems' => false,
                        'options' => [
                            'class' => 'nav nav-pills navbar-nav navbar-right black main-nav'
                        ],
                        'items' => Team::teamMenu(),
                        'encodeLabels' => false,
                    ]
                )?>
                </div>
            </div>
        </div>
    </div>
        <header id="intro">
            <div class="container">
                <div class="table">
                    <div class="header-text">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h3 class="light white">Football is a game about feelings and intelligence.</h3>
                                <h1 class="white typed">FootballStat! Stat about football</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?= $content ?>



        <div class="page-buffer"></div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 text-center-mobile">
                    <h3 class="white">Footballstat</h3>
                    <h5 class="light regular light-white">Футбольная Статистика лучших европейских чемпионатов</h5>
                </div>
            </div>
            <div class="row bottom-footer text-center-mobile">
                <div class="col-sm-8">
                    <p>&copy; 2016 All Rights Reserved. Powered by</p>
                </div>
                <div class="col-sm-4 text-right text-center-mobile">
                    <ul class="social-footer">
                        <li><a href=""><i class="fa fa-facebook"></i></a></li>
                        <li><a href=""><i class="fa fa-twitter"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

<!-- Holder for mobile navigation -->
<div class="mobile-nav">
    <ul>
    </ul>
    <a href="#" class="close-link"><i class="fa fa-close"></i></a>
</div>

<?php
    Modal::begin([
        'header' => '<h3 class="text-center">Подробная статистика<h3>',
        'size' => 'modal-lg',
        'id' => 'match',
        'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>'
    ]);
    Modal::end();
        Modal::begin([
            'header' => '<h3 class="text-center">Подробная статистика<h3>',
            'size' => 'modal-lg',
            'id' => 'modal',
            'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>'
        ]);

        echo "<div id='modalContent'></div>";

        Modal::end();
?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>