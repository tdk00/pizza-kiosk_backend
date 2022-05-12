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

				<form method="post" action="<?=base_url()?>products_admin/update/<?= $productData[0]['id'] ?>" enctype="multipart/form-data">
					<div class="row">
						<!-- left column -->
						<div class="col-md-6 mt-2">
							<!-- general form elements -->
							<div class="card card-primary">
								<div class="card-header">
									<h3 class="card-title">Yeni Məhsul</h3>
								</div>
								<!-- /.card-header -->
								<!-- form start -->
								<div class="card-body">
									<div class="form-group">
										<label for="product_name">Məhsul adı (Az) </label>
										<input value="<?=$productData[0]['name_az']?>" type="text" name="product_name_az" class="form-control" id="product_name_az" required placeholder="Məhsul adı (Azərbaycanca)">
									</div>
									<div class="form-group">
										<label for="product_name">Məhsul adı (En) </label>
										<input value="<?=$productData[0]['name_en']?>" type="text" name="product_name_en" class="form-control" id="product_name_en" placeholder="Məhsul adı (İngiliscə)">
									</div>
									<div class="form-group">
										<label for="product_name">Məhsul adı (Ru) </label>
										<input value="<?=$productData[0]['name_ru']?>" type="text" name="product_name_ru" class="form-control" id="product_name_ru" placeholder="Məhsul adı (Rusca)">
									</div>
									<div class="form-group">
										<label for="product_name">Məhsulun Kateqoriyası </label>
										<select name="product_category" class="form-control" required>
											<option value=""> Seçin </option>
											<?php foreach ( $categories as $category ): ?>
												<option <?= $productData[0]['category_id'] == $category['id'] ? 'selected': ''?> value="<?= $category['id'] ?>"> <?= $category['name_az'] ?> </option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group">
										<label for="exampleInputFile">Məhsul şəkli </label>
										<div class="input-group">
											<div class="custom-file">
												<input type="file" name="product_image" class="custom-file-input" id="product_image">
												<label class="custom-file-label" for="exampleInputFile">Fayl seçin</label>
											</div>
											<div class="input-group-append">
												<span class="input-group-text">Upload</span>
											</div>
										</div>
									</div>
								</div>
								<!-- /.card-body -->

							</div>
						</div>

						<div id="sizes" class="col-md-6 mt-2">
							<?php foreach ( $sizes as $sizeKey => $sizeValue ): ?>
								<div class="card card-danger">
									<div class="card-header">
										<h3 class="card-title">Ölçü</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
												<i class="fas fa-minus"></i>
											</button>
											<?php if( $sizeKey > 0): ?>
											<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
												<i class="fas fa-times"></i>
											</button>
											<?php endif; ?>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-4">
												<label for="product_name"> Ölçü adı ( Az ) </label>
												<input value="<?= $sizeValue['name_az'] ?>" type="text" name="sizes[<?= ( $sizeKey + 1 ) ?>][size_name_az]" class="form-control" required>
											</div>
											<div class="col-4">
												<label for="product_name"> Ölçü adı ( En ) </label>
												<input value="<?= $sizeValue['name_en'] ?>" type="text" name="sizes[<?= ( $sizeKey + 1 ) ?>][size_name_en]" class="form-control" >
											</div>
											<div class="col-4">
												<label for="product_name"> Ölçü adı ( Ru ) </label>
												<input value="<?= $sizeValue['name_ru'] ?>" type="text" name="sizes[<?= ( $sizeKey + 1 ) ?>][size_name_ru]" class="form-control" >
											</div>
										</div>
										<div class="row pt-2 mt-2">
											<div class="col-4">
												<label for="product_name"> Ölçünün Qiyməti <br> ( nöqtə ilə ayrılmış. Məs: 3.40 ) </label>
												<input value="<?= $sizeValue['price'] ?>" type="number" step="0.01" name="sizes[<?= ( $sizeKey + 1 ) ?>][size_price]"  class="form-control"  required>
											</div>

											<div class="col-4">
												<label for="product_name"> Barkod  </label><br><br>
												<input value="<?= $sizeValue['barkod'] ?>" type="text" name="sizes[<?= ( $sizeKey + 1 ) ?>][size_barkod]" class="form-control" required >
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
						<div id="add_size" class="col-md-6"><button type="button" class="btn btn-primary" style="float:right;">Ölçü Əlavə et</button></div>
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


<script>
	$("#add_size").on('click', function () {
		$("#sizes").append(
			'<div class="card card-danger">' +
			'<div class="card-header">' +
			'<h3 class="card-title">Yeni Ölçü</h3>' +
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
			'<div class="col-4">' +
			'<label for="product_name"> Ölçü adı ( Az )</label>' +
			'<input type="text" name="sizes['+ ( $("#sizes .card").length + 1 ) + '][size_name_az]" class="form-control"  required>' +
			'</div>' +
			'<div class="col-4">' +
			'<label for="product_name"> Ölçü adı ( En ) </label>' +
			'<input type="text" name="sizes['+ ( $("#sizes .card").length + 1 ) + '][size_name_en]" class="form-control" >' +
			'</div>' +
			'<div class="col-4">' +
			'<label for="product_name"> Ölçü adı ( Ru ) </label>' +
			'<input type="text" name="sizes['+ ( $("#sizes .card").length + 1 ) + '][size_name_ru]" class="form-control" >' +
			'</div>' +
			'</div>' +
			'<div class="row pt-2 mt-2">' +
			'<div class="col-4">' +
			'<label for="product_name"> Ölçünün Qiyməti </label>' +
			'<input type="number" step="0.01" name="sizes['+ ( $("#sizes .card").length + 1 ) + '][size_price]"  class="form-control"  required>' +
			'</div>' +
			'<div class="col-4">' +
			'<label for="barkod"> Barkod </label>' +
			'<input type="text" name="sizes['+ ( $("#sizes .card").length + 1 ) + '][size_barkod]"  class="form-control"  required>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'<div class="card-footer"></div>' +
			'</div>')	});
</script>

<script>
	$("#sizes").on('click', ".card .card-header .card-tools button[data-card-widget='remove']",  function (){
		$(this).parents('.card').remove();
	})
</script>

</body>
</html>
