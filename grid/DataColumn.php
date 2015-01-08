<?php

namespace creators\metroui\grid;

class DataColumn extends \yii\grid\DataColumn
{
    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }
        $model = $this->grid->filterModel;
        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                return Html::beginTag('div', ['class' => 'input-control select']) .
                    Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . ' ' . $error .
                    Html::closeTag('div');
            } else {
                return Html::beginTag('div', ['class' => 'input-control text']) .
                    Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . ' ' . $error .
                    Html::closeTag('div');
            }
        } else {
            return parent::renderFilterCellContent();
        }
    }
}
