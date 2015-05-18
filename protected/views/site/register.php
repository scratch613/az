<?php
/* @var $this ProfilesController */
/* @var $model Profiles */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profiles-register-form',
	'htmlOptions' => array("role"=>"form"),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'lname', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'lname', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'lname'); ?>
	</div>


	<div class="row form-group">
		<?php echo $form->labelEx($model,'fname', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'fname', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'fname'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'mname', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'mname', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'mname'); ?>
	</div>


	<div class="row form-group">
		<?php echo $form->labelEx($model,'nickname', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'nickname', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'nickname'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'email', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'email', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'phone', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'phone', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'login', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'login', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'login'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'password', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'password', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'password2', array('class'=>'col-sm-2 control-label')); ?>
		<?php echo $form->textField($model,'password2', array('class'=>'col-sm-4')); ?>
		<?php echo $form->error($model,'password2'); ?>
	</div>



	<div class="row form-group buttons">
		<div class="col-sm-6">
		<?php echo CHtml::submitButton('Зарегистрироваться'); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->