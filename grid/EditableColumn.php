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

    public $refreshGrid = false;

    public $disabled = false;

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

        unset($this->editableOptions['disabled']);

        if ($this->disabled instanceof \Closure) {
            $this->editableOptions['clientOptions']['disabled'] = call_user_func_array($this->disabled, [$model]);
        }
        else {
            $this->editableOptions['clientOptions']['disabled'] = $this->disabled;
        }

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
        $view = $this->grid->getView();
        /*
        EditableAsset::register($view);

        $selector = "[rel=\"{$this->options['rel']}\"]";
         */

        $grid = ";jQuery('#{$this->grid->options['id']}')";
        $view->registerJs("if ($.fn.editable != null) $.fn.editable.defaults.ajaxOptions = {success: function(){ {$grid}.yiiGridView('applyFilter'); }}" );
        $script = <<<JS
{$grid}.find('.editable-input').each(function() {
    var \$input = $(this);
    \$input.on('editableSuccess', function() {
        {$grid}.yiiGridView('applyFilter');
    });
});
JS;
        $view->registerJs($script);
    }
}
