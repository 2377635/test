<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Description of JuiAsset
 *
 * @author Ilya Podgursky <2377635@gmail.com>
 */
class JuiAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $css = [
        'themes/smoothness/jquery-ui.min.css',
    ];
    public $js = [
        'jquery-ui.min.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
