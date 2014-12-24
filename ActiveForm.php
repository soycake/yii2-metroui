<?php
/**
 * @link http://www.fintegro.com/
 * @copyright Copyright (c) 2015 Fintegro Inc
 * @license BSD-3-Clause
 */

namespace fintegro\metroui;

/**
 * A Metro UI enhanced version of [[\yii\widgets\ActiveForm]].
 *
 * This class mainly adds the [[layout]] property to choose a Metro UI form layout.
 * So for example to render form you would:
 *
 * ```php
 * use fintegro\metroui\ActiveForm;
 *
 * $form = ActiveForm::begin([])
 * ```
 *
 * @see \fintegro\metroui\ActiveField for details on the [[fieldConfig]] options
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
    public $fieldClass = 'fintegro\metroui\ActiveField';
    /**
     * @var array HTML attributes for the form tag. Default is `['role' => 'form']`.
     */
    public $options = ['role' => 'form'];
}
