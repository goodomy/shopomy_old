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
class VerificationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
   public $css = [
	'plugins/bootstrap/css/bootstrap.min.css',
	'plugins/bootstrap/css/bootstrap-reset.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'css/style.css',
	'css/style-responsive.css',
    ];
    
    public $js = [
	'plugins/jquery-1.8.3.min.js',
	'plugins/html5shiv.js',
	'plugins/respond.min.js',
        'plugins/jquery.js',
	'plugins/bootstrap/js/bootstrap.min.js',
    ];
    
    public $jsOptions = array(
    'position' => \yii\web\View::POS_HEAD
    );
    
    //public $depends = [
    //    'yii\web\YiiAsset',
    //    'yii\bootstrap\BootstrapAsset',
    //];
}
