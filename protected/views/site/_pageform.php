<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'page-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('cols'=>80, 'rows'=>10)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">

	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>
<?php 
$this->widget('ext.redactor.ImperaviRedactorWidget',array(
    // the textarea selector
    'selector'=>'#Page_content',
    // Redactor options
    'options'=>array(),
));

?>


</div><!-- form -->