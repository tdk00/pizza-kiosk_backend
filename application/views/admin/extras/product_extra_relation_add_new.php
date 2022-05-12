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
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/dist/css/custom.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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

				<form method="post" action="<?=base_url()?>extras_admin/update_relation/<?=$size_and_product_details['id']?>/" enctype="multipart/form-data">
					<div class="row">
						<!-- left column -->
						<div class="col-md-6 mt-2">
							<!-- general form elements -->
							<div class="card card-primary">
								<div class="card-header">
									<h3 class="card-title"><?=( $size_and_product_details['product_details']['name_az'])?></h3>
								</div>
								<!-- /.card-header -->
								<!-- form start -->
								<div class="card-body">
									<dl class="row">
										<dt class="col-sm-4">Ölçü</dt>
										<dd class="col-sm-8"> <?= $size_and_product_details['name_az']?> </dd>
										</dd>
									</dl>
								</div>
								<!-- /.card-body -->

							</div>
						</div>

						<div id="extras" class="col-md-6">
							<?php foreach ( $related_extras as $extraKey => $extraValue ): ?>
								<div class="card card-danger">
									<div class="card-header">
										<h3 class="card-title">EXTRA</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
												<i class="fas fa-minus"></i>
											</button>
											<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
												<i class="fas fa-times"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Extra seçin</label>
													<select name="extras[<?= ( $extraKey + 1 ) ?>][extra_id]" class="form-control select2" style="width: 100%;" required>
														<?php foreach ( $all_extras as $extra ):?>
															<option <?= $extra['id'] ==  $extraValue['extra_id'] ? 'selected' : ''?>
																	value="<?= $extra['id']?>"><?=$extra['name_az']?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="col-4">
												<label for="product_extra"> Extra sayı ( default ) </label>
												<input value="<?= $extraValue['extra_count'] ?>" type="number" step="1" name="extras[<?= ( $extraKey + 1 ) ?>][extra_count]" class="form-control" required >
											</div>
										</div>
									</div>
									<!-- /.card-body -->

									<div class="card-footer">
									</div>
								</div>
							<?php endforeach; ?>


						</div>
						<!--/.col (left) -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-6"><button type="submit" class="btn btn-primary">Təsdiqlə</button></div>
						<div id="add_extra" class="col-md-6"><button type="button" class="btn btn-primary" style="float:right;">Ekstra Əlavə et</button></div>
					</div>

				</form>
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
<!-- Select2 -->
<script src="<?=base_url()?>assets/admin/plugins/select2/js/select2.full.min.js"></script>

<script>
	$(function () {
		//Initialize Select2 Elements
		$('.select2').select2()

		//Initialize Select2 Elements
		$('.select2bs4').select2({
			theme: 'bootstrap4'
		});
	});

</script>

<script>
	$("#add_extra").on('click', function () {
		$("#extras").append(
	'<div class="card card-danger">' +
		'<div class="card-header">' +
			'<h3 class="card-title">Extra</h3>' +
			'<div class="card-tools">' +
				'<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">' +
					'<i class="fas fa-minus"></i>' +
				'</button>' +
				'<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">' +
					'<i class="fas fa-times"></i>' +
				'</button>' +
			'</div>' +
		'</div>		' +
		'<div class="card-body">' +
				'<div class="row">' +
					'<div class="col-md-6">' +
						'<div class="form-group">' +
							'<label>Extra seçin</label>' +
							'<select name="extras['+ ( $("#extras .card").length + 1 ) + '][extra_id]" class="form-control select2" style="width: 100%;" required>' +
								<?php foreach ( $all_extras as $extra ):?>
									'<option value="<?= $extra['id']?>" selected="selected"><?=$extra['name_az']?></option>'+
								<?php endforeach;?>
							'</select>' +
						'</div>' +
					'</div>' +
					'<div class="col-4">' +
						'<label for="product_name"> Ekstra sayı </label>' +
						'<input type="number" step="1" name="extras['+ ( $("#extras .card").length + 1 ) + '][extra_count]" class="form-control" required>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'<div class="card-footer"></div>' +
	'</div>')	});
</script>

<script>
	$("#extras").on('click', ".card .card-header .card-tools button[data-card-widget='remove']",  function (){
		$(this).parents('.card').remove();
	})
</script>



</body>
</html>
