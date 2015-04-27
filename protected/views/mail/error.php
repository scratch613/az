<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$revision,
    'attributes'=>array(
        'id',
        array('label'=>'Site', 'value'=>$revision->site->ftp->user . "@" . $revision->site->ftp->host),
        'revision_number',
        'state',
        'created',
        'updated',
    ),
)); ?>
Files: <?php echo $this->renderPartial('//mail/_files', array('model' =>$revision)); ?>