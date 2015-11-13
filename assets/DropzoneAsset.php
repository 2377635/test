<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Description of DropzoneAsset
 *
 *  @author Ilya Podgursky <2377635@gmail.com>
 */
class DropzoneAsset extends AssetBundle
{
    public $sourcePath = '@vendor/enyo/dropzone';
    public $css = [
        'dist/min/dropzone.min.css',
    ];
    public $js = [
        'dist/min/dropzone.min.js',
    ];
    public $depends = [
    ];
}
