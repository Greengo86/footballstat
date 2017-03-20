<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\Team;
use kartik\nav\NavX;
use kartik\helpers\Html;
use app\components\AlertWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>Админка | <?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <div class="preloader">
        <img src="/img/loader.gif" alt="Preloader image">
    </div>


    <nav class="navbar">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <p><a class="navbar-brand black fa fa-futbol-o fa-spin fa-2x" href="<?= Url::to(['/']) ?>" alt="Главная страница"></a></p>
            </div>

            <!--Навигационный виджет NavX - лиги и команды-->
            <?php echo NavX::widget(
                [
                    'activateParents' => true,
                    'activateItems' => false,
                    'options' => [
                        'class' => 'nav-pills navbar-nav navbar-right black main-nav'
                    ],
                    'items' => Team::teamMenu(),
                'encodeLabels' => false,
                ]
            )?>

        </div>

    </nav>

    <div class="team-content">
        <?php echo AlertWidget::widget()?>
        <?= $content ?>
    </div>

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
                    <p>&copy; 2016 All Rights Reserved. Powered by
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

    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage() ?>