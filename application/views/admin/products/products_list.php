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
								<h3 class="card-title">Məhsullar</h3>
								<div class="card-tools">
									<div class="input-group input-group-sm" style="width: 150px;">
										<a href="<?=base_url()?>products_admin/add_new" class="btn btn-block btn-primary">Yeni Məhsul</a>
									</div>
								</div>

							</div>
							<!-- /.card-header -->
							<div class="card-body table-responsive p-0">
								<table class="table table-hover text-nowrap">
									<thead>
									<tr>
										<th>ID</th>
										<th>Məhsul adı ( Az ) </th>
										<th>Məhsul adı ( En ) </th>
										<th>Məhsul adı ( Ru ) </th>
										<th>Ekstra</th>
										<th>Sil</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($products as $product) : ?>
										<tr style="cursor: pointer"  onclick="window.location='<?=base_url().'products_admin/edit/'.$product['id']?>';">
											<td> <?=$product['id']?> </td>
											<td> <?=$product['name_az']?> </td>
											<td> <?=$product['name_en']?> </td>
											<td> <?=$product['name_ru']?> </td>
											<td onclick="event.stopPropagation(); return false;"> <button class="btn btn-success productExtrasButton" data-product-id="<?=$product['id']?>"> Extralar </td>
											<td onclick="event.stopPropagation(); return false;"> <button class="btn btn-danger deleteProduct" data-product-id="<?=$product['id']?>"> Sil </td>
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

<div class="modal fade" id="modal-primary">
	<div class="modal-dialog">
		<div class="modal-content bg-primary">
			<form method="post" action="<?=base_url()?>extras_admin/per_add_new/" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title">Ölçü Seçimi</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<select id="product_size" name="product_size" class="form-control" required>
						<option value=""> Seçin </option>
					</select>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-outline-light">Təsdiqlə</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

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
	$(".deleteProduct").on('click', function () {
		let product_id = $(this).data('product-id');
		swal({
			title: "Əminsiniz ?",
			text: "Mehsul silinərkən, içərisindəki bütün ölçülər də silinəcəkdir",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
			.then((willDelete) => {
				if (willDelete) {
					window.location.href = "<?=base_url()?>products_admin/delete/" + product_id + "";
				} else {

				}
			});
	})
</script>

<script>
	$(".productExtrasButton").on('click', function ()
	{
		let product_id = $(this).data('product-id');
		$.post( "<?=base_url()?>products_admin/get_sizes_by_product_id/" + product_id + "")
			.done(function( data ) {
				var sizes = [];
				data = JSON.parse( data );
				if( data.status )
				{
					sizes = data.data;
					$("#product_size").html('<option value=""> Seçin </option>');
					for (var i = 0; i < sizes.length; i ++)
					{
						$("#product_size").append('<option value='+ sizes[i].id +'> ' + sizes[i].name_az + ' </option>')
					}

					$('#modal-primary').modal('show');
				}
				else
				{
					swal({
						title: "Ölçü tapılmadı",
						text: "Ekstra əlavə etmək üçün , əvvəlcə ölçü əlavə edin",
						icon: "warning",
						dangerMode: true,
					})
				}
			});
		//window.location.href = "<?=base_url()?>extras_admin/per_add_new/" + product_id + "";
	});

</script>

</body>
</html>
