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

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<!-- /.row -->
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Sifarişlər</h3>
								<div class="card-tools">
								</div>
							</div>

							<!-- /.card-header -->
							<div class="card-body table-responsive p-0">
								<table class="table table-hover text-nowrap">
									<thead>
									<tr>
										<th>ID</th>
										<th>Sifariş nömrəsi</th>
										<th>Sifariş tarixi ( Az ) </th>
										<th> Ödəniş növü </th>
										<th> Yemək yeri </th>
										<th> Yekun məbləğ </th>
										<th>
											<select id="status-select" class="form-control col-md-8">
												<option <?= $status == 4 ? 'selected' : '' ?> value="4"> Hamısı </option>
												<option <?= $status == 0 ? 'selected' : '' ?> value="0"> Təsdiq edilməmiş </option>
												<option <?= $status == 1 ? 'selected' : '' ?> value="1"> Hazırlanır </option>
												<option <?= $status == 2 ? 'selected' : '' ?> value="2"> Hazırdır </option>
												<option <?= $status == 3 ? 'selected' : '' ?> value="3"> Təhvil verildi </option>
											</select>
										</th>
										<th>Statusu Dəyiş</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($orders as $order):
										$status = "";
										switch ($order['status']) {
											case 0:
												$status = "Təsdiq edilməmiş";
												break;
											case 1:
												$status = "Hazırlanır";
												break;
											case 2:
												$status = "Hazırdır";
												break;
											case 3:
												$status = "Təhvil verildi";
												break;
											default:
												$status = "";
										}
										?>
										<tr style="cursor: pointer"  onclick="window.location='<?=base_url().'orders_admin/order_details/'.$order['id']?>';">
											<td> <?=$order['id']?> </td>
											<td> <?=$order['order_number']?> </td>
											<td> <?=$order['date']?> </td>
											<td> <?=$order['payment_type']?> </td>
											<td> <?=$order['is_takeaway'] == 1 ? "Paket" : "Restoranda"?> </td>
											<td> <?=$order['total']?> </td>
											<td> <?=$status?> </td>
											<td onclick="event.stopPropagation(); return false;">
												<select class="form-control status-change" data-order-id="<?=$order['id']?>">
													<option <?= $order['status'] == 0 ? 'selected' : '' ?> value="0"> Təsdiq edilməmiş </option>
													<option <?= $order['status'] == 1 ? 'selected' : '' ?> value="1"> Hazırlanır </option>
													<option <?= $order['status'] == 2 ? 'selected' : '' ?> value="2"> Hazırdır </option>
													<option <?= $order['status'] == 3 ? 'selected' : '' ?> value="3"> Təhvil verildi </option>
												</select>
											</td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<!-- /.card-body -->
						</div>
						<!-- /.card -->
					</div>
				</div>
				<!-- /.row -->
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
	$("#status-select").on('change', function () {
			let status = $(this).val();
			window.location.href = "<?=base_url()?>orders_admin/index/" + status + "";
	});

	$(".status-change").on('change', function () {
		let status = $(this).val();
		let order_id = $(this).data('order-id');
		window.location.href = "<?=base_url()?>orders_admin/change_status/" + status + "/" + order_id;
	});


</script>

</body>
</html>
