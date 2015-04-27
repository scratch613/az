<?php
$this->breadcrumbs=array(
	'Patricipants',
);

$this->menu=array(

	array('label'=>'Manage Patricipants', 'url'=>array('admin')),
);
?>

<h1>Patricipants</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
