<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace creators\metroui\widgets;

/**
 * A Metro UI enhanced version of [[\yii\widgets\ActiveForm]].
 *
 * This class mainly adds the [[layout]] property to choose a Metro UI form layout.
 * So for example to render form you would:
 *
 * ```php
 * use creators\metroui\widgets\ActiveForm;
 *
 * $form = ActiveForm::begin([])
 * ```
 *
 * @see \creators\metroui\widgets\ActiveField for details on the [[fieldConfig]] options
 * @see http://metroui.org.ua/forms.html
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     * @see fieldConfig
     */
    public $fieldClass = '\creators\metroui\widgets\ActiveField';
    /**
     * @var array HTML attributes for the form tag. Default is `['role' => 'form']`.
     */
    public $options = ['role' => 'form'];
    /**
     * @var string the CSS class that is added to a field container when the associated attribute has validation error.
     */
    public $errorCssClass = 'error-state';
    /**
     * @var string the CSS class that is added to a field container when the associated attribute is successfully validated.
     */
    public $successCssClass = 'success-state';
}
