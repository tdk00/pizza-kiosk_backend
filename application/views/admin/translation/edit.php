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
					<!-- left column -->
					<div class="col-md-12 mt-2">
						<!-- general form elements -->
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">Tərcümələr </h3>
							</div>
							<!-- /.card-header -->
							<!-- form start -->
								<div class="card-body">
									<?php foreach ( $words as $word ): ?>
									<div class="row mt-2" style="margin-bottom: 30px">
										<div class="col-4">
											<input style="background-color: #e7e7e7" data-wordkey="<?=$word['word_key']?>" data-wordlang="az"  value="<?= $word['az']?>" type="text" class="form-control" placeholder="">
										</div>
										<div class="col-4">
											<input style="background-color: #e7e7e7" data-wordkey="<?=$word['word_key']?>" data-wordlang="en" value="<?= $word['en']?>" type="text" class="form-control" placeholder="">
										</div>
										<div class="col-4">
											<input style="background-color: #e7e7e7" data-wordkey="<?=$word['word_key']?>" data-wordlang="ru" value="<?= $word['ru']?>" type="text" class="form-control" placeholder="">
										</div>
									</div>
									<?php endforeach; ?>

								</div>
								<!-- /.card-body -->

						</div>

					</div>
					<!--/.col (left) -->
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

<script src="<?=base_url()?>assets/admin/dist/js/pages/dashboard3.js"></script>

<script>
	$("input").focus(function(){
		$(this).css('background-color', "#FFFFFF");
	});

	$("input").blur(function(){
		let wordkey = $(this).data('wordkey');
		let lang = $(this).data('wordlang');
		let word = $(this).val();
		console.log( word );
		$.post( "<?=base_url()?>translationAdmin/update/" + wordkey + "/" + lang + "/", { "word" : word })
			.done(function( data ) {
				console.log(data);
			});
		$(this).css('background-color', "#e7e7e7");
	});




</script>

</body>
</html>
