<?php 
	$cart->init($session);
	if (!isset($currency)) $currency = 'USD';
?>
<div id="cartMessage"></div>
<div id="cart" class="cart"<?php if(!$cart->hasItems()):?> style="display: none"<?php endif; ?>>
	<?php if ($cart->hasItems()): ?>				
		<h1><?php echo $html->link('Shopping Cart', array('controller' => 'products', 'action' => 'view_cart')); ?></h1>
		<strong>Item(s):</strong> <span id="cartQuantity"><?php echo $cart->getValue('quantity'); ?></span>
		<span class="price"><?php echo $number->currency($cart->getValue('subtotal'), $currency); ?></span>
	<?php else: ?>
		Loading Cart...
	<?php endif; ?>
</div>
