<?php echo $content; ?>
<?php 

if (! Yii::app()->user->isGuest && Yii::app()->user->getUser()->role == 'admin') {
	echo CHtml::link('Редактировать', array('site/editpage/id/' . $page));
}

?>
