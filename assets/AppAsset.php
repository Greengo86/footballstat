<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //    Normalize
        'css/normalize.css',
        //    Bootstrap
//        'css/bootstrap.css',
        //    Owl
        'css/owl.css',
        //    Animate.css
        'css/animate.css',
        //    Font Awesome
        'fonts/font-awesome-4.1.0/css/font-awesome.min.css',
        //    Elegant Icons
        'fonts/eleganticons/et-icons.css',
        //    Main style
        'css/bazi.css',
        'css/owl.carousel.min.css',
        'css/owl.carousel_new.min.css',
        'css/owl.theme.default.min.css',
        'css/custom.css'
    ];
    public $js = [
//       'js/jquery-1.11.1.min.js',
        'js/wow.min.js',
        'js/typewriter.js',
        'js/jquery.onepagenav.js',
        'js/main.js',
        'js/owl.carousel.js',
//        'js/owl.carousel.min.js',
//        'js/jquery.mousewheel.min.js',
//       'js/bootstrap.min.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
//        'rmrevin\yii\fontawesome\AssetBundle'
    ];
}
