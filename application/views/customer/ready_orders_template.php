<?php foreach ( $ready_orders as $order ): ?>
<div class="card card-primary card-outline">
	<div class="card-header">
		<h5 class="card-title"><?= $order['order_number'] ?></h5>
		<div class="card-tools">
		</div>
	</div>
</div>
<?php endforeach;?>
