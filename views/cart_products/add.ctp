<div class="cartProducts form">
<?php echo $form->create('CartProduct');?>
	<fieldset>
 		<legend><?php __('Add CartProduct');?></legend>
	<?php
		echo $form->input('name');
		echo $form->input('description');
		echo $form->input('taxable');
		echo $form->input('price');
		echo $form->input('visible');
		echo $form->input('active');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List CartProducts', true), array('action'=>'index'));?></li>
	</ul>
</div>
