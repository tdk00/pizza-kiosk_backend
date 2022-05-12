<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>KingsPizza Kiosk</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/fontawesome-free/css/all.min.css">
	<!-- IonIcons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/dist/css/adminlte.min.css">

	<script src="<?=base_url()?>assets/admin/dist/js/sweetalert.min.js"></script>
</head>

<body>
<div class="wrapper">
	<div class="content-wrapper kanban" style="margin-left: 0">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row">
				</div>
			</div>
		</section>

		<section class="content pb-3">
			<div class="container-fluid h-100">
				<div class="card card-row card-danger" style="width: 800px;">
					<div class="card-header">
						<h3 class="card-title">
							To Do
						</h3>
					</div>
					<div id="unready-orders" class="card-body">
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
					</div>
				</div>
				<div class="card card-row card-success" style="width: 800px;">
					<div class="card-header">
						<h3 class="card-title">
							Done
						</h3>
					</div>
					<div id="ready-orders" class="card-body">
						<?php foreach ( $ready_orders as $order ): ?>
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h5 class="card-title"><?= $order['order_number'] ?></h5>
									<div class="card-tools">
									</div>
								</div>
							</div>
						<?php endforeach;?>
					</div>
				</div>
			</div>
		</section>
	</div>
	<!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->


<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?=base_url()?>assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?=base_url()?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="<?=base_url()?>assets/admin/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?=base_url()?>assets/admin/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="--><?//=base_url()?><!--assets/admin/dist/js/demo.js"></script>-->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=base_url()?>assets/admin/dist/js/pages/dashboard3.js"></script>


<script>
	setInterval(function(){get_orders();}, 10000);
	function get_orders(){
		var feedback = $.ajax({
			type: "POST",
			url: "<?=base_url()?>welcome/get_orders",
			async: false
		}).responseText;

		let orders = JSON.parse(feedback);

		$('#unready-orders').html(orders.unready_orders);
		$('#ready-orders').html(orders.ready_orders);
	}
</script>

</body>
</html>
