<?php
$this->breadcrumbs=array(
	'Patricipants'=>array('index'),
	$model->id,
);

$this->menu=array(

	array('label'=>'Update Patricipants', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Patricipants', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Patricipants', 'url'=>array('admin')),
);
?>

<h1>View Patricipants #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'email',
		'firstname',
		'lastname',
		'country',
		'city',
		'type',
		'confirmed_18',
		'paid',
	),
)); ?>
