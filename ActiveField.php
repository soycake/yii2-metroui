<?php
/**
 * @link http://www.fintegro.com/
 * @copyright Copyright (c) 2015 Fintegro Inc
 * @license BSD-3-Clause
 */

namespace fintegro\metroui;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * A Metro UI enhanced version of [[\yii\widgets\ActiveField]].
 *
 * This class adds some useful features to [[\yii\widgets\ActiveField|ActiveField]] to render all
 * sorts of Metro UI form fields in different form layouts:
 *
 * todo: edit this section.
 *
 * - [[inputTemplate]] is an optional template to render complex inputs, for example input groups
 * - [[horizontalCssClasses]] defines the CSS grid classes to add to label, wrapper, error and hint
 *   in horizontal forms
 * - [[inline]]/[[inline()]] is used to render inline [[checkboxList()]] and [[radioList()]]
 * - [[enableError]] can be set to `false` to disable to the error
 * - [[enableLabel]] can be set to `false` to disable to the label
 * - [[label()]] can be used with a `boolean` argument to enable/disable the label
 *
 * There are also some new placeholders that you can use in the [[template]] configuration:
 *
 * - `{beginLabel}`: the opening label tag
 * - `{labelTitle}`: the label title for use with `{beginLabel}`/`{endLabel}`
 * - `{endLabel}`: the closing label tag
 * - `{beginWrapper}`: the opening wrapper tag
 * - `{endWrapper}`: the closing wrapper tag
 *
 * The wrapper tag is only used for some layouts and form elements.
 *
 * Note that some elements use slightly different defaults for [[template]] and other options.
 * You may want to override those predefined templates for checkboxes, radio buttons, checkboxLists
 * and radioLists in the [[\yii\widgets\ActiveForm::fieldConfig|fieldConfig]] of the
 * [[\yii\widgets\ActiveForm]]:
 *
 * - [[checkboxTemplate]] the template for checkboxes in default layout
 * - [[radioTemplate]] the template for radio buttons in default layout
 * - [[horizontalCheckboxTemplate]] the template for checkboxes in horizontal layout
 * - [[horizontalRadioTemplate]] the template for radio buttons in horizontal layout
 * - [[inlineCheckboxListTemplate]] the template for inline checkboxLists
 * - [[inlineRadioListTemplate]] the template for inline radioLists
 *
 * Example:
 *
 * ```php
 * use yii\metro ui\ActiveForm;
 *
 * $form = ActiveForm::begin(['layout' => 'horizontal'])
 *
 * // Form field without label
 * echo $form->field($model, 'demo', [
 *     'inputOptions' => [
 *         'placeholder' => $model->getAttributeLabel('demo'),
 *     ],
 * ])->label(false);
 *
 * // Inline radio list
 * echo $form->field($model, 'demo')->inline()->radioList($items);
 *
 * // Control sizing in horizontal mode
 * echo $form->field($model, 'demo', [
 *     'horizontalCssClasses' => [
 *         'wrapper' => 'col-sm-2',
 *     ]
 * ]);
 *
 * // With 'default' layout you would use 'template' to size a specific field:
 * // echo $form->field($model, 'demo', [
 * //     'template' => '{label} <div class="row"><div class="col-sm-4">{input}{error}{hint}</div></div>'
 * // ]);
 *
 *  // Input group
 *  echo $form->field($model, 'demo', [
 *      'inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>',
 *  ]);
 *
 *  ActiveForm::end();
 *  ```
 *
 * @see \fintegro\metroui\ActiveForm
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
    public $template = "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}";
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
     * @var string the template for radios in default layout
     */
    public $radioTemplate = "<div class=\"input-control radio\" data-role=\"input-control\">\n{beginLabel}\n{input}\n<span class=\"check\"></span>\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
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
                $input = isset($this->parts['{input}']) ?
                    $this->parts['{input}'] : Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
                $this->parts['{input}'] = strtr($this->inputTemplate, ['{input}' => $input]);
            }
        }
        return parent::render($content);
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
        $this->wrapperOptions['class'] = str_replace('text', 'password', $this->wrapperOptions['class']);

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
     * @inheritdoc
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

        return parent::radio($options, false);
    }

    /**
     * @inheritdoc
     */
    public function checkboxList($items, $options = [])
    {
        if ($this->inline) {
            if (!isset($options['template'])) {
                $this->template = $this->inlineCheckboxListTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            if (!isset($options['itemOptions'])) {
                $options['itemOptions'] = [
                    'labelOptions' => ['class' => 'checkbox-inline'],
                ];
            }
        }  elseif (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) {
                return '<div class="checkbox">' . Html::checkbox($name, $checked, ['label' => $label, 'value' => $value]) . '</div>';
            };
        }
        parent::checkboxList($items, $options);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function radioList($items, $options = [])
    {
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
        }  elseif (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) {
                return '<div class="radio">' . Html::radio($name, $checked, ['label' => $label, 'value' => $value]) . '</div>';
            };
        }
        parent::radioList($items, $options);
        return $this;
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
            'wrapperOptions' => [
                'class' => 'input-control text',
                'data-role' => 'input-control',
            ],
            'options' => [],
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
