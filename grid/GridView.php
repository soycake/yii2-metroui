<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace yii2metroui\grid;

class GridView extends \yii\grid\GridView
{
    /**
    * @var string the default data column class if the class name is not explicitly specified when configuring a data column.
    * Defaults to '\yii2metroui\grid\DataColumn'.
    */
    public $dataColumnClass = '\yii2metroui\grid\DataColumn';

    public $tableOptions = ['class' => 'table striped bordered'];
}
