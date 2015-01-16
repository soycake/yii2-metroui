<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace yii2metroui\grid;

use yii\helpers\Html;

class DataColumn extends \yii\grid\DataColumn
{
    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if ($this->filter !== false && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'error-state');
                $error = Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            }
            else {
                $error = '';
            }

            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                return Html::beginTag('div', ['class' => 'input-control select']) .
                    Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . ' ' . $error .
                    Html::endTag('div');
            }
            else {
                return Html::beginTag('div', ['class' => 'input-control text']) .
                    Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . ' ' . $error .
                    Html::endTag('div');
            }
        }
        else {
            return parent::renderFilterCellContent();
        }
    }
}
