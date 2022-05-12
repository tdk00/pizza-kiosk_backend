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
								<h3 class="card-title">Extralar</h3>
								<div class="card-tools">
									<div class="input-group input-group-sm" style="width: 150px;">
										<a href="<?=base_url()?>extras_admin/add_new" class="btn btn-block btn-primary">Yeni Extra</a>
									</div>
								</div>
							</div>

							<!-- /.card-header -->
							<div class="card-body table-responsive p-0">
								<table class="table table-hover text-nowrap">
									<thead>
									<tr>
										<th>ID</th>
										<th>Extra adı ( Az ) </th>
										<th>Extra adı ( En ) </th>
										<th>Extra adı ( Ru ) </th>
										<th> Sil </th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($extras as $extra) : ?>
									<tr style="cursor: pointer"  onclick="window.location='<?=base_url().'extras_admin/edit/'.$extra['id']?>';">
										<td> <?=$extra['id']?> </td>
										<td> <?=$extra['name_az']?> </td>
										<td> <?=$extra['name_en']?> </td>
										<td> <?=$extra['name_ru']?> </td>
										<td onclick="event.stopPropagation(); return false;"> <button class="btn btn-danger deleteExtra" data-category-id="<?=$extra['id']?>"> Sil </td>
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
	$(".deleteExtra").on('click', function () {
		let product_id = $(this).data('category-id');
		swal({
			title: "Əminsiniz ?",
			text: "Extra silinərkən, aid olduğu bütün məhsulların tərkibindən ləğv silinəcəkdir",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
			.then((willDelete) => {
				if (willDelete) {
					window.location.href = "<?=base_url()?>extras_admin/delete/" + product_id + "";
				} else {

				}
			});
	})
</script>

</body>
</html>
