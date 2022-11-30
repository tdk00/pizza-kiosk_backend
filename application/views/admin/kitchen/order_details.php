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

	<?php $this->load->view('admin/leftMenuKitchen'); ?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h4>
							<i class="fas fa-globe"></i> Sifarişin məhsulları
						</h4>
					</div>
					<!-- /.col -->
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
										<?php foreach ( $items as $item ): ?>
										<?php
											$extraNameCountString = "";
											foreach ( $item['extras'] as $extrakey => $extraValue )
											{
												if( $extraValue['itemExtraDefaultCount'] ==  $extraValue['itemExtraCount'] ) // extra sayinda deyishiklik edilmedise
												{
													continue;
												}

												if( $extraValue['itemExtraDefaultCount'] > 0 && $extraValue['itemExtraCount'] == 0 ) // icinde olan extralardan hansinisa 0 edibse
												{
													$extraNameCountString .= $extraValue['itemExtraName']." - olmasin";
													if( ! (count($item['extras']) == ( $extrakey + 1 )) )
													{
														$extraNameCountString .= ", ";
													}
													continue;
												}

												if( $extraValue['itemExtraDefaultCount'] > 0 && $extraValue['itemExtraCount'] < $extraValue['itemExtraDefaultCount'] )
												{
													$extraNameCountString .=  $extraValue['itemExtraName'] ." az ( ". $extraValue['itemExtraDefaultCount'] ." yox  ". ( $extraValue['itemExtraCount'] )." olsun ) ";
													if( ! (count($item['extras']) == ( $extrakey + 1 )) )
													{
														$extraNameCountString .= ", ";
													}
													continue;
												}


												$extraNameCountString .= "  əlavə - ". ( $extraValue['itemExtraCount'] - $extraValue['itemExtraDefaultCount'] )." " .$extraValue['itemExtraName'] ."  ( ". $extraValue['itemExtraDefaultCount'] ." yox  ". ( $extraValue['itemExtraCount'] )." olsun ) " ;
												if( ! (count($item['extras']) == ( $extrakey + 1 )) )
												{
													$extraNameCountString .= ", ";
												}
											}
										?>
										<tr>
											<td><?= $item['item_count'] ?> </td>
											<td><?= $item['product_name'] ?>
											<td><?= $item['product_size_name'] ?></td>
											<td><?= $extraNameCountString ?></td>
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
							<div class="row no-print">
								<div class="col-12">
									<a href="<?=base_url()."/kitchen_admin/order_ready_confirm/". $order_details['id'] ?>" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Hazırdır
									</a>
								</div>
							</div>
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
