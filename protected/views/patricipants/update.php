<?php
$this->breadcrumbs=array(
	'Patricipants'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

if (Yii::app()->user->getUser()->role == 'admin') {

	$this->menu=array(

		array('label'=>'View Patricipants', 'url'=>array('view', 'id'=>$model->id)),
		array('label'=>'Manage Patricipants', 'url'=>array('admin')),
	);
}
?>

<h1>Update Patricipants <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>