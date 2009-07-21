<div class="cartProducts index">
<h2><?php __('CartProducts');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('description');?></th>
	<th><?php echo $paginator->sort('taxable');?></th>
	<th><?php echo $paginator->sort('price');?></th>
	<th><?php echo $paginator->sort('visible');?></th>
	<th><?php echo $paginator->sort('active');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($cartProducts as $cartProduct):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $cartProduct['CartProduct']['id']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['created']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['modified']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['name']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['description']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['taxable']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['price']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['visible']; ?>
		</td>
		<td>
			<?php echo $cartProduct['CartProduct']['active']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $cartProduct['CartProduct']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $cartProduct['CartProduct']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $cartProduct['CartProduct']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $cartProduct['CartProduct']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New CartProduct', true), array('action'=>'add')); ?></li>
	</ul>
</div>
