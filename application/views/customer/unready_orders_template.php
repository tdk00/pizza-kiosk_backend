<?php foreach ( $unready_orders as $order ): ?>
	<div class="card card-danger card-outline">
		<div class="card-header">
			<h5 class="card-title"><?= $order['order_number'] ?></h5>
			<div class="card-tools">
				<a href="#" class="btn btn-tool">
					<i class="fas fa-pen"></i>
				</a>
			</div>
		</div>
	</div>
<?php endforeach;?>
