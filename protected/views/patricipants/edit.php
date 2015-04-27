<?php
$this->breadcrumbs=array(
	'Site'=>array('index'),
	'My Account',
);

?>

<h1>My Accoubt </h1>

<?php echo $this->renderPartial('_userform', array('model'=>$model)); ?>