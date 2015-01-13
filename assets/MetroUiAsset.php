<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace yii2metroui\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Metro UI css files.
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class MetroUiAsset extends AssetBundle
{
    public $sourcePath = '@bower/metro-ui-css';
    public $css = [
        'min/metro-bootstrap.min.css',
        'min/metro-responsive.min.css',
        'min/iconFont.min.css',
    ];
}
