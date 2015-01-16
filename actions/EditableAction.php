<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace yii2metroui\actions;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;

/**
 * \yii2metroui\action\EditableAction is the base action class for Editable widget.
 *
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class EditableAction extends \yii\base\Action
{
    public $modelClass = null;


    /**
     * Initializes the action.
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException('Field \'modelClass\' cannot be empty');
        }
    }

    /**
     * Runs the action
     * @return boolean
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $class = $this->modelClass;

        $pk = Yii::$app->getRequest()->post('pk');
        $pk = unserialize(base64_decode($pk));

        $attribute = Yii::$app->getRequest()->post('name');
        $value = Yii::$app->getRequest()->post('value');

        /*
         * if ($attribute === null) {
         * throw new BadRequestHttpException("'name' parameter cannot be empty.");
         * }
         * if ($value === null) {
         * throw new BadRequestHttpException("'value' parameter cannot be empty.");
         * }
         */

        $model = $class::findOne($pk);

        /*
         * if (!$model) {
         * if ($this->forceCreate) { // only useful for models with one editable attribute or no validations
         * $model = new $class;
         * } else {
         * throw new BadRequestHttpException('Entity not found by primary key ' . $pk);
         * }
         * }
         */

        $model->$attribute = $value;

        if ($model->validate([$attribute])) {
            return $model->save(false);
        }
        else {
            throw new BadRequestHttpException($model->getFirstError($attribute));
        }
    }
}
