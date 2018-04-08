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
    public $css = 
    [
        'plugins/bootstrap/css/bootstrap.min.css',
		'plugins/bootstrap/css/bootstrap-reset.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'css/style.css',
		'css/style-responsive.css',
        'css/slidebars.css',
        'css/custom.css',
		'css/owl.carousel.css',
        'plugins/bootstrap-datepicker/css/datepicker.css',
		'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css',
        'plugins/morris.js-0.4.3/morris.css',
		'plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css',
		'plugins/bootstrap-fileupload/bootstrap-fileupload.css',
    ];
    
    public $js = 
    [
        'plugins/jquery-1.8.3.min.js',
        'plugins/html5shiv.js',
        'plugins/respond.min.js',
        'plugins/jquery.js',
        'plugins/bootstrap/js/bootstrap.min.js',
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'plugins/jquery.dcjqaccordion.2.7.js',
        'plugins/jquery.scrollTo.min.js',
        'plugins/jquery.nicescroll.js',
        'plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js',
        'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js',
        'plugins/bootstrap-fileupload/bootstrap-fileupload.js',
        'scripts/jquery.validate.min.js',
        'plugins/owl.carousel.js',
        //'scripts/toucheffects.js',
        //'scripts/slidebars.min.js',
    ];
    
		public $jsOptions = array(
		'position' => \yii\web\View::POS_HEAD
		);
    
    //public $depends = [
    //    'yii\web\YiiAsset',
    //    'yii\bootstrap\BootstrapAsset',
    //];
}
