<?php
    $colspan = 6;
    $noone = '<tr><td colspan="'.$colspan.'" class="noone">Пока никого...</td></tr>';

    function getNextMember() {
        static $num = 0;
        return ++$num;
    }



?>

<table class="memberslist">
    <tr class="listheader">
        <th>№</th>
        <th>Никнейм</th>
        <th>Страна / Город</th>
        <th>Аваратка</th>
        <th>Участие</th>
        <th style="width: 40px;">Оплата</th>
    </tr>

    <!-- суперспонсоры -->
    <?php if ($dataProviderSuper->totalItemCount) { ?>
        <tr class="listheader">
            <td colspan="<?php echo $colspan?>">Суперспонсоры</td>
        </tr>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProviderSuper,
                'itemView'=>'_listitem',
                'template' => '{items}',
                'emptyText' => $noone,
            )); ?>
    <?php } ?>



    <!-- спонсоры -->
    <?php if ($dataProviderSponsors->totalItemCount) { ?>
        <tr class="listheader">
            <td colspan="<?php echo $colspan?>">Спонсоры</td>
        </tr>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProviderSponsors,
                'itemView'=>'_listitem',
                'template' => '{items}',
            	'emptyText' => $noone,
            )); ?>
    <?php } ?>



    <!-- участники -->
    <?php if ($dataProviderMembers->totalItemCount) { ?>
        <tr class="listheader">
            <td colspan="<?php echo $colspan?>">Участники</td>
        </tr>

            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProviderMembers,
                'itemView'=>'_listitem',
                'template' => '{items}',
            	'emptyText' => $noone,
            )); ?>
    <?php } ?>


    <!-- гости -->
    <?php if ($dataProviderGuests->totalItemCount) { ?>
        <tr class="listheader">
            <td colspan="<?php echo $colspan?>">Гости</td>
        </tr>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProviderGuests,
                'itemView'=>'_listitem',
                'template' => '{items}',
            	'emptyText' => $noone,
            )); ?>

    <?php } ?>
</table>


