<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace yii2metroui\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Editable widget.
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class EditableAsset extends AssetBundle
{
    public $sourcePath = '@bower/x-editable/dist';
    public $css = [
        'jqueryui-editable/css/jqueryui-editable.css'
    ];
    public $js = [
        'jqueryui-editable/js/jqueryui-editable.min.js'
    ];
    public $depends = [
        '\yii\jui\JuiAsset'
    ];
}
