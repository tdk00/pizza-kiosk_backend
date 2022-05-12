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

<body class="hold-transition sidebar-mini">
<div class="wrapper">


	<!-- Main Sidebar Container -->

	<?php $this->load->view('admin/leftMenu'); ?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Sifariş detalları - <?= $order_details['order_number'] ?> </h1>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>

		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<!-- Main content -->
						<div class="invoice p-3 mb-3">
							<!-- title row -->
							<div class="row">
								<div class="col-12">
									<h4>
										<i class="fas fa-globe"></i> Sifarişin məhsulları
									</h4>
								</div>
								<!-- /.col -->
							</div>

							<!-- Table row -->
							<div class="row">
								<div class="col-12 table-responsive">
									<table class="table table-striped">
										<thead>
										<tr>
											<th>Say</th>
											<th>Mehsul adi</th>
											<th>Ölçü </th>
											<th>Ekstralar </th>
											<th>Sifariş nömrəsi</th>
										</tr>
										</thead>
										<tbody>
										<?php foreach ( $order_products as $product ): ?>
										<?php
											$extraNameCountString = "";
											foreach ( $product['extras'] as $extrakey => $extraValue )
											{
												$extraNameCountString .= $extraValue['extra_name']." ( ". $extraValue['extra_count']." )";
												if( ! (count($product['extras']) == ( $extrakey + 1 )) )
												{
													$extraNameCountString .= ", ";
												}
											}
										?>
										<tr>
											<td><?= $product['product_count'] ?> </td>
											<td><?= $product['product_name'] ?>
											<td><?= $product['size_name'] ?></td>
											<td><?= $extraNameCountString ?></td>
											<td><?= $order_details['order_number'] ?></td>
											<td><?= $order_details['order_number'] ?></td>
										</tr>
										<?php endforeach; ?>

										</tbody>
									</table>
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->

							<!-- this row will not appear when printing -->
						</div>
						<!-- /.invoice -->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
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
	$(".orderDetailsButton").on('click', function () {
		let order_id = $(this).data('order-id');
		window.location.href = "<?=base_url()?>kitchen_admin/order_details/" + order_id + "";
	})
</script>

</body>
</html>
