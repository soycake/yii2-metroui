<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace creators\metroui\grid;

use Yii;
use yii\helpers\Html;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    protected function renderHeaderCellContent()
    {
        $name = rtrim($this->name, '[]') . '_all';
        $id = $this->grid->options['id'];
        $options = json_encode([
            'name' => $this->name,
            'multiple' => $this->multiple,
            'checkAll' => $name,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->grid->getView()->registerJs("jQuery('#$id').yiiGridView('setSelectionColumn', $options);");

        if ($this->header !== null || !$this->multiple) {
            return parent::renderHeaderCellContent();
        } else {
            return '<div class="input-control checkbox"><label>'.
                    Html::checkBox($name, false, ['class' => 'select-on-check-all']).
                    '<span class="check"></span></label></div>';
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->checkboxOptions instanceof Closure) {
            $options = call_user_func($this->checkboxOptions, $model, $key, $index, $this);
        } else {
            $options = $this->checkboxOptions;
            if (!isset($options['value'])) {
                $options['value'] = is_array($key)
                    ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                    : $key;
            }
        }

        return '<div class="input-control checkbox"><label>'.
                Html::checkbox($this->name, !empty($options['checked']), $options).
                '<span class="check"></span></label></div>';
    }
}
