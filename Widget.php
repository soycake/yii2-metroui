<?php
/**
 * @link http://www.fintegro.com/
 * @copyright Copyright (c) 2015 Fintegro Inc
 * @license BSD-3-Clause
 */

namespace fintegro\metroui;

use Yii;
use yii\helpers\Json;

/**
 * \yii\bootstrap\Widget is the base class for all metro ui widgets.
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array the options for the underlying Metro UI JS plugin.
     * Please refer to the corresponding Metro UI plugin Web page for possible options.
     * For example, [this page](http://metroui.org.ua/dialog.html) shows
     * how to use the "Dialog" plugin and the supported options (e.g. "draggable").
     */
    public $clientOptions = [];
    /**
     * @var array the event handlers for the underlying Metro UI JS plugin.
     * Please refer to the corresponding Metro UI plugin Web page for possible events.
     * For example, [this page](http://metroui.org.ua/dialog.html) shows
     * how to use the "Dialog" plugin and the supported events (e.g. "onShow").
     */
    public $clientEvents = [];


    /**
     * Initializes the widget.
     * This method will register the metro ui asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Registers a specific Metro UI plugin and the related events
     * @param string $name the name of the Bootstrap plugin
     */
    protected function registerPlugin($name)
    {
        $view = $this->getView();

        MetroUiPluginAsset::register($view);

        $id = $this->options['id'];

        if ($this->clientOptions !== false) {
            $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            $js = "jQuery('#$id').$name($options);";
            $view->registerJs($js);
        }

        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }
}
