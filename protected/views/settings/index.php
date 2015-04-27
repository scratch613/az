<?php echo CHtml::beginForm();?>


<?php echo $this->renderPartial('_render_controls') ?>


<?php foreach($settings as $key=>$value): ?>

	<?php $this->beginWidget('system.web.widgets.CClipWidget', array('id'=>Yii::t('settings', $key))); ?>
			<?php getcontrols($key, $value, Yii::app()->par); ?>
	<?php $this->endWidget(); ?>

<?php endforeach; ?> 
 
<?php
$tabParameters = array();
foreach($this->clips as $key=>$clip)
    $tabParameters['tab'.(count($tabParameters)+1)] = array('title'=>$key, 'content'=>$clip);
?>
 
<?php $this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters)); ?>

<?php echo CHtml::submitButton(Yii::t('main', 'Save'));?>

<?php echo CHtml::endForm();?>

