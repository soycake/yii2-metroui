<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace creators\metroui\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * A Metro UI enhanced version of [[\yii\widgets\ActiveField]].
 *
 * This class adds some useful features to [[\yii\widgets\ActiveField|ActiveField]] to render all
 * sorts of Metro UI form fields in different form layouts:
 *
 * @see \creators\metroui\widgets\ActiveForm
 * @see http://metroui.org.ua/forms.html
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var string the template that is used to arrange the label, the input field, the error message and the hint text.
     * The following tokens will be replaced when [[render()]] is called: `{label}`, `{input}`, `{error}` and `{hint}`.
     */
    public $template = "{label}\n{input}\n{hint}\n{error}\n";
    /**
     * @var string|null optional template to render the `{input}` placeholder content
     */
    public $inputTemplate;
    /**
     * @var array options for the wrapper tag, used in the `{beginWrapper}` placeholder
     */
    public $wrapperOptions = [];
    /**
     * @var string the template for checkboxes in default layout
     */
    public $checkboxTemplate = "<div class=\"input-control checkbox\" data-role=\"input-control\">\n{beginLabel}\n{input}\n<span class=\"check\"></span>\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for switch in default layout
     */
    public $switchTemplate = "<div class=\"input-control switch\" data-role=\"input-control\">\n{beginLabel}\n{input}\n<span class=\"check\"></span>\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for radios in default layout
     */
    public $radioTemplate = "<div class=\"input-control radio\" data-role=\"input-control\">\n{beginLabel}\n{input}\n<span class=\"check\"></span>\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for radios in default layout
     */
    public $fileTemplate = "<div class=\"input-control file\" data-role=\"input-control\">\n{beginLabel}\n{input}\n<button class=\"btn-file\"></button>\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    /**
     * @var boolean whether to render the error. Default is `true` except for layout `inline`.
     */
    public $enableError = true;
    /**
     * @var boolean whether to render the label. Default is `true`.
     */
    public $enableLabel = true;

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $layoutConfig = $this->createLayoutConfig($config);
        $config = ArrayHelper::merge($layoutConfig, $config);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{beginWrapper}'])) {
                $options = $this->wrapperOptions;
                $tag = ArrayHelper::remove($options, 'tag', 'div');
                $this->parts['{beginWrapper}'] = Html::beginTag($tag, $options);
                $this->parts['{endWrapper}'] = Html::endTag($tag);
            }
            if ($this->enableLabel === false) {
                $this->parts['{label}'] = '';
                $this->parts['{beginLabel}'] = '';
                $this->parts['{labelTitle}'] = '';
                $this->parts['{endLabel}'] = '';
            } elseif (!isset($this->parts['{beginLabel}'])) {
                $this->renderLabelParts();
            }
            if ($this->enableError === false) {
                $this->parts['{error}'] = '';
            }
            if ($this->inputTemplate) {
                $input = isset($this->parts['{input}'])
                    ? $this->parts['{input}']
                    : Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
                $this->parts['{input}'] = strtr($this->inputTemplate, ['{input}' => $input]);
            }
        }

        return parent::render($content);
    }

    /**
     * Renders the opening tag of the field container.
     * @return string the rendering result.
     */
    public function begin()
    {
        if ($this->form->enableClientScript) {
            $clientOptions = $this->getClientOptions();
            if (!empty($clientOptions)) {
                $this->form->attributes[] = $clientOptions;
            }
        }
        $inputID = Html::getInputId($this->model, $this->attribute);
        $attribute = Html::getAttributeName($this->attribute);
        $options = $this->options;
        $class = isset($options['class']) ? [$options['class']] : [];
        $class[] = "field-$inputID";
        if ($this->model->isAttributeRequired($attribute)) {
            $class[] = $this->form->requiredCssClass;
        }
        if ($this->model->hasErrors($attribute)) {
            $class[] = $this->form->errorCssClass;
        }
        $options['class'] = implode(' ', $class);
        $options['style'] = 'height: auto;';
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        return Html::beginTag($tag, $options);
    }

    /**
     * Renders the closing tag of the field container.
     * @return string the rendering result.
     */
    public function end()
    {
        return Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div');
    }

    /**
     * Renders a password input.
     * This method will generate the "name" and "value" tag attributes automatically for the model attribute
     * unless they are explicitly specified in `$options`.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[Html::encode()]].
     * @return static the field object itself
     */
    public function passwordInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activePasswordInput($this->model, $this->attribute, $options);
        $this->options['class'] = str_replace('text', 'password', $this->options['class']);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        if ($enclosedByLabel) {
            if (!isset($options['template'])) {
                $this->template = $this->checkboxTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            $this->labelOptions['class'] = null;
        }

        return parent::checkbox($options, false);
    }

    /**
     * Renders a radio button.
     * This method will generate the "checked" tag attribute according to the model attribute value.
     * @param array $options the tag options in terms of name-value pairs. The following options are specially handled:
     *
     * - uncheck: string, the value associated with the uncheck state of the radio button. If not set,
     *   it will take the default value '0'. This method will render a hidden input so that if the radio button
     *   is not checked and is submitted, the value of this attribute will still be submitted to the server
     *   via the hidden input.
     * - label: string, a label displayed next to the radio button. It will NOT be HTML-encoded. Therefore you can pass
     *   in HTML code such as an image tag. If this is coming from end users, you should [[Html::encode()|encode]] it to prevent XSS attacks.
     *   When this option is specified, the radio button will be enclosed by a label tag.
     * - labelOptions: array, the HTML attributes for the label tag. This is only used when the "label" option is specified.
     *
     * The rest of the options will be rendered as the attributes of the resulting tag. The values will
     * be HTML-encoded using [[Html::encode()]]. If a value is null, the corresponding attribute will not be rendered.
     * @param boolean $enclosedByLabel whether to enclose the radio within the label.
     * If true, the method will still use [[template]] to layout the checkbox and the error message
     * except that the radio is enclosed by the label tag.
     * @return static the field object itself
     */
    public function radio($options = [], $enclosedByLabel = true)
    {
        if ($enclosedByLabel) {
            if (!isset($options['template'])) {
                $this->template = $this->radioTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            $this->labelOptions['class'] = null;
        }

        $enclosedByLabel = false;

        if ($enclosedByLabel) {
            $this->parts['{input}'] = Html::activeRadio($this->model, $this->attribute, $options);
            $this->parts['{label}'] = '';
        } else {
            if (isset($options['label']) && !isset($this->parts['{label}'])) {
                $this->parts['{label}'] = $options['label'];
                if (!empty($options['labelOptions'])) {
                    $this->labelOptions = $options['labelOptions'];
                }
            }
            unset($options['labelOptions']);
            $options['label'] = null;
            $this->parts['{input}'] = Html::activeRadio($this->model, $this->attribute, $options);
        }
        $this->adjustLabelFor($options);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function switchInput($options = [], $enclosedByLabel = true)
    {
        if ($enclosedByLabel) {
            if (!isset($options['template'])) {
                $this->template = $this->switchTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            $this->labelOptions['class'] = null;
        }

        return parent::checkbox($options, false);
    }

    public function fileInput($options = [], $enclosedByLabel = true)
    {
        if ($enclosedByLabel) {
            if (!isset($options['template'])) {
                $this->template = $this->fileTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            $this->labelOptions['class'] = null;
        }
        $this->options['class'] = str_replace('text', 'file', $this->options['class']);

        return parent::fileInput($options, $enclosedByLabel);
    }

    /**
     * @inheritdoc
     */
    public function checkboxList($items, $options = [])
    {
        /*
        if ($this->inline) {
            if (!isset($options['template'])) {
                $this->template = $this->inlineCheckboxListTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            if (!isset($options['itemOptions'])) {
                $options['itemOptions'] = [
                    'labelOptions' => ['class' => 'inline-block'],
                ];
            }
        } elseif (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) {
                return
                    Html::checkbox($name, $checked, ['label' => $label, 'value' => $value]);
            };
        }

        parent::checkboxList($items, $options);
        return $this;
        */
    }

    /**
     * @inheritdoc
     */
    public function radioList($items, $options = [])
    {
        /*
        if ($this->inline) {
            if (!isset($options['template'])) {
                $this->template = $this->inlineRadioListTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            if (!isset($options['itemOptions'])) {
                $options['itemOptions'] = [
                    'labelOptions' => ['class' => 'radio-inline'],
                ];
            }
        } elseif (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) {
                return '<div class="radio">' .
                    Html::radio($name, $checked, ['label' => $label, 'value' => $value]) .
                    '</div>';
            };
        }
        parent::radioList($items, $options);
        return $this;
         */
    }

    /**
     * @inheritdoc
     */
    public function label($label = null, $options = [])
    {
        if (is_bool($label)) {
            $this->enableLabel = $label;
        } else {
            $this->enableLabel = true;
            $this->renderLabelParts($label, $options);
            parent::label($label, $options);
        }
        return $this;
    }

    /**
     * @param array $instanceConfig the configuration passed to this instance's constructor
     * @return array the layout specific default configuration for this instance
     */
    protected function createLayoutConfig($instanceConfig)
    {
        $config = [
            'hintOptions' => [
                'tag' => 'p',
                'class' => 'help-block',
            ],
            'errorOptions' => [
                'tag' => 'p',
                'class' => 'help-block help-block-error',
            ],
            'options' => [
                'class' => 'input-control text',
                'data-role' => 'input-control',
            ],
            'inputOptions' => [],
            'labelOptions' => [],
        ];

        return $config;
    }

    /**
     * @param string|null $label the label or null to use model label
     * @param array $options the tag options
     */
    protected function renderLabelParts($label = null, $options = [])
    {
        $options = array_merge($this->labelOptions, $options);
        if ($label === null) {
            if (isset($options['label'])) {
                $label = $options['label'];
                unset($options['label']);
            } else {
                $attribute = Html::getAttributeName($this->attribute);
                $label = Html::encode($this->model->getAttributeLabel($attribute));
            }
        }
        $this->parts['{beginLabel}'] = Html::beginTag('label', $options);
        $this->parts['{endLabel}'] = Html::endTag('label');
        $this->parts['{labelTitle}'] = $label;
    }
}
