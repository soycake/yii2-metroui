<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace creators\metroui\grid;

use creators\metroui\widgets\Editable;
use creators\metroui\assets\EditableAsset;
use yii\helpers\Html;
use yii\helpers\Url;

class EditableColumn extends DataColumn
{
    public $editableOptions;

    public function init()
    {
        parent::init();

        $rel = $this->attribute . '_editable';
        $this->options['rel'] = $rel;

        $this->registerClientScript();
    }

    public function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);

        $this->editableOptions['model'] = $model;
        $this->editableOptions['attribute'] = $this->attribute;
        //$this->editableOptions['value'] = $value;
        //$this->editableOptions['pk'] = $key;
        $this->editableOptions['name'] = $this->attribute;

        /*
        $rel = $this->attribute . '_editable';
        $this->editableOptions['options']['rel'] = $rel;
        $this->editableOptions['clientOptions']['target'] = "[rel={$rel}]";
        */

        return Editable::widget($this->editableOptions);


        /*
        $this->options['data-url'] = Url::toRoute($this->editableOptions['url']);
        $this->options['data-pk']  = base64_decode(serialize($key));
        $this->options['data-name'] = $this->attribute;
        $this->options['data-mode'] = $this->editableOptions['mode'];
        $this->options['data-type'] = $this->editableOptions['type'];

        return Html::a($value, null, $this->options);
         */
    }

    protected function registerClientScript()
    {
        /*
        $view = $this->grid->getView();
        EditableAsset::register($view);

        $selector = "[rel=\"{$this->options['rel']}\"]";

        $view->registerJs(";jQuery('$selector').editable();");
         */
    }
}
