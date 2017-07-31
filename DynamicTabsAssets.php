<?php
/**
 * @Copyright Copyright (c) 2017 @DynamicTabsAssets.php By Kami
 * @License http://www.yuzhai.tv/
 */

namespace yesela\dynamictabs;


use yii\web\AssetBundle;

class DynamicTabsAssets extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $js = [
        'js/bootstrap-dynamic-tabs.js',
        'js/bootstrap-dynamic-tabs-closable.js',
    ];
    public $css = [
        'css/bootstrap-dynamic-tabs.css',
        'css/jquery.scrolling-tabs.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

}