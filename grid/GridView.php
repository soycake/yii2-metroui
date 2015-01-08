<?php

namespace creators\metroui\grid;

class GridView extends \yii\grid\GridView
{
    /**
    * @var string the default data column class if the class name is not explicitly specified when configuring a data column.
    * Defaults to 'creators\metroui\grid\DataColumn'.
    */
    public $dataColumnClass = '\creators\metroui\grid\DataColumn';

    public $tableOptions = ['class' => 'table striped bordered'];
}
