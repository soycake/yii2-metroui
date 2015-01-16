<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace yii2metroui\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii2metroui\assets\EditableAsset;

/**
 * An extended editable widget for Yii Framework 2.
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class Editable extends \yii\widgets\InputWidget
{
    const MODE_INLINE = 'inline';

    const TYPE_TEXT   = 'text';

    const SEND_ALWAYS = 'always';
    const SEND_AUTO   = 'auto';


    public $inputOptions = [];

    public $inputContainerOptions = [];

    public $editableButtons;

    //public $submitButton = ['class' => 'button success'];

    //public $resetButton = ['class' => 'button warning'];

    public $clientOptions = [];

    public $mode = self::MODE_INLINE;

    public $type = self::TYPE_TEXT;

    public $url;

    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException("'Url' property must be specified.");
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel()
                ? Html::getInputId($this->model, $this->attribute)
                : $this->getId();
        }

        parent::init();

        $this->initOptions();
    }

    public function initOptions()
    {
        Html::addCssClass($this->inputContainerOptions, 'input-control');

        switch($this->type) {
        case self::TYPE_TEXT:
            Html::addCssClass($this->inputContainerOptions, self::TYPE_TEXT);
            //$this->clientOptions['tpl'] = $this->renderFormField();
            $this->renderFormField();
            break;
        default:
            break;
        }
    }

    public function run()
    {
        if ($this->hasModel()) {
            if ($this->value !== null) {
                if (is_string($this->value)) {
                    $this->value = ArrayHelper::getValue($this->model, $this->value);
                }
                else {
                    $this->value = call_user_func($this->value, $this->model);
                }
            }
            else {
                $this->value = ArrayHelper::getValue($this->model, $this->attribute);
            }
        }

        $pk = $this->hasModel() ? $this->model->getPrimaryKey() : null;
        $this->options['class'] = "{$this->options['id']}_{$pk}_editable";

        echo Html::a($this->value, null, $this->options) . "\n";
        $this->registerAssets();
    }

    public function renderFormField()
    {
        $field = '<input type="text">';
        //Html::hiddenInput('hasEditable', 0) . "\n";

        /*
        if ($this->hasModel()) {
            $field .= Html::activeInput($this->type, $this->model, $this->attribute, $this->inputOptions);
        }
        else {*/
            //$field .= Html::input('text', $this->name, /*$this->value*/null, $this->inputOptions);
        //}

        //$field .= Html::button('', ['class' => 'btn-clear']);

        $this->editableButtons = Html::submitButton(Html::tag('i', null, ['class' => 'icon-checkmark']), ['class' => 'editable-submit small', 'style' => 'height: 34px; width: 34px;']) .
            Html::button(Html::tag('i', null, ['class' => 'icon-cancel-2']), ['class' => 'editable-cancel small', 'style' => 'height: 34px; width: 34px;']);

        //return Html::tag('div', $field, $this->inputContainerOptions);
        //return $field;
    }

    public function registerAssets()
    {
        $view = $this->getView();
        EditableAsset::register($view);

        $this->clientOptions['url']  = Url::toRoute($this->url);
        $this->clientOptions['mode'] = $this->mode;
        $this->clientOptions['type'] = $this->type;
        $this->clientOptions['name'] = $this->attribute ? : $this->name;

        $this->clientOptions['clear'] = null;

        $pk = $this->hasModel() ? $this->model->getPrimaryKey() : null;
        $this->clientOptions['pk'] = base64_encode(serialize($pk));

        if ($this->hasModel() && $this->model->getIsNewRecord()) {
            $this->clientOptions['send'] = self::SEND_ALWAYS;
        }

        $options = json_encode($this->clientOptions);

        $id = 'jQuery(".' . (isset($this->clientOptions['target']) ? $this->clientOptions['target'] : "{$this->options['id']}_{$pk}_editable") . '")';

        $view->registerJs("$.fn.editableform.buttons = '{$this->editableButtons}'" );
        $view->registerJs('$.fn.editableform.template = \'<form class="form-inline editableform"> \
            <div class="control-group">  \
            <div><div class="editable-input input-control text"></div><div class="editable-buttons"></div></div> \
            <div class="editable-error-block"></div>  \
            </div>  \
            </form>\';');
        $view->registerJs("{$id}.editable({$options})");
    }

    /*
    public function getForm()
    {
        return $this->_form;
    }
     */
}
