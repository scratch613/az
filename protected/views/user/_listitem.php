<tr>


    <td>
        <?php echo CHtml::encode(getNextMember() /*$data->id*/); ?>
    </td>

    <td>
        <?php echo CHtml::encode($data->nickname); ?>
    </td>

    <td>
        <?php echo CHtml::encode($data->country); echo "/"; echo CHtml::encode($data->city); ?>
    </td>
    <td>
	<?php if ($data->avatar) { ?>
	<a href="/img/avatars/<?php echo crc32($data->email) . $data->avatar ?>"><img src="/img/avatars/<?php echo 'av_'. crc32($data->email) . $data->avatar ?>"></a>
	<?php } else { ?>
	    Без аватарки
	<?php } ?>
    </td>
    <td>
	    <?php echo CHtml::encode(str_replace(array('member', 'guest'), array('участник', 'гость'), $data->type)); ?>
    </td>

    <td style="text-align: center;">
	    <?php 
		if ($data->paid > 0 ) {
		    if ($data->paid < 550) {
			if ($data->type == 'guest') {
			    if ($data->paid >= 150) {
				echo "<b>$</b>";
			    } else {
    				echo '$/';
			    } 
			} else {
    			    echo '$/';
    			}
		    } else {
			echo '<b>$</b>';
		    }
		}
 
	    ?>
    </td>


</div>