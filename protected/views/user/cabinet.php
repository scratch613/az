<h1>Личный кабинет <?php echo $patricipant->email; ?></h1>

<?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>

<div class="infowrap">
    <h3 for-class="personal" class="active">Личные данные</h3>
    <h3 for-class="password">Сменить пароль</h3>
    <div class="clear"></div>
	<div class="info personal active">
		<div class="inner">
			<div class="avatar">
			<?php if ($patricipant->avatar) { ?>
				<img src="/img/avatars/<?php echo 'thumb_'. crc32($patricipant->email) .$patricipant->avatar ?>" />
			<?php }else {?>
				<img src="/img/slowpoke.png" />
			<?php } ?>
            <div>Заменить аватарку:</div>
            <?php echo CHtml::fileField('User[avatar]'); ?>
		</div>
		<div class="userinfo"></div>

			<?php echo CHtml::submitButton('Сохранить данные'); ?>

        </div>

	</div>

	<div class="info password">
		<div class="inner">
			<label><span>Новый пароль</span><?php echo CHtml::textField('User[newpass1]', '', array('size'=>32,'maxlength'=>32)); ?></label>
            <label><span>Еще раз</span><?php echo CHtml::textField('User[newpass2]', '', array('size'=>32,'maxlength'=>32)); ?></label>
            <?php echo CHtml::submitButton('Сменить пароль'); ?>
		</div>
	</div>
</div>
<?php echo CHtml::endForm(); ?>

<script>
$(function(){
    $('.infowrap h3').click(function(){
        var el = $(this);
        var fclass = el.attr('for-class');
        $('.active').removeClass('active');
        el.addClass('active');
        $('.infowrap .'+fclass).addClass('active');
    });
});


</script>