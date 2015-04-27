<?php
Yii::import('ext.ConsoleGrid');

$this->widget('ConsoleGrid', array(
	'id'=>'revisions-grid',
	'dataProvider'=>$model->allFiles(),
    'enablePagination' => false,
    'enableSorting'=>false,
	'filter'=>null,
	'columns'=>array(
		'id',
		'fullpath',
		'size',
		array('value'=>'date("Y-m-d H:i:s", $data->time)', 'header'=>'Date'),
		'oldsize',
		array('value'=>'date("Y-m-d H:i:s", $data->oldtime)', 'header'=>'Date'),
		
	),
)); ?>
