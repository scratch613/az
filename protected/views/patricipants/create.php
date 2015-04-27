<?php
$this->breadcrumbs=array(
	'Patricipants'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Patricipants', 'url'=>array('index')),
	array('label'=>'Manage Patricipants', 'url'=>array('admin')),
);
?>

<h1>Create Patricipants</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>