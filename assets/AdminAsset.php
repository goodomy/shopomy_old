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
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = 
    [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
		'css/ionicons.min.css',
        'css/AdminLTE.min.css',
		'css/custom.css',
        'css/skins/_all-skins.min.css',
		'plugins/bootstrap-fileupload/bootstrap-fileupload.css',
		'plugins/multiselect/bootstrap-select/css/bootstrap-select.min.css',
		'plugins/multiselect/jquery-multi-select/css/multi-select.css',
		'plugins/multiselect/select2/css/select2.min.css',
		'plugins/multiselect/select2/css/select2-bootstrap.min.css',
		'plugins/bootstrap-datepicker/css/datepicker.css'
    ];
    
    public $js = 
    [
        'scripts/jquery-2.2.3.min.js',
        'scripts/bootstrap.min.js',
        'scripts/fastclick.js',
		'scripts/app.min.js',
		'scripts/demo.js',
		'scripts/jquery.validate.min.js',
		'plugins/bootstrap-fileupload/bootstrap-fileupload.js',
		'plugins/multiselect/bootstrap-select/js/bootstrap-select.min.js',
		'plugins/multiselect/jquery-multi-select/js/jquery.multi-select.js',
		'plugins/multiselect/select2/js/select2.full.min.js',
		'plugins/multiselect/scripts/components-multi-select.min.js',
		'plugins/multiselect/multiselect.js',
		'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
		'plugins/chartjs/Chart.min.js',
		
		
    ];
    
	public $jsOptions = array(
	'position' => \yii\web\View::POS_HEAD
	);
    
    //public $depends = [
    //    'yii\web\YiiAsset',
    //    'yii\bootstrap\BootstrapAsset',
    //];
}
