<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AdminLTE 3 | Log in</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/dist/css/adminlte.min.css">

	<script src="<?=base_url()?>assets/admin/dist/js/sweetalert.min.js"></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<a href="<?=base_url()?>assets/admin/index2.html"><b>Admin</b> - KingsPizza</a>
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Zəhmət Olmasa Giriş Edin</p>

				<div class="input-group mb-3">
					<input id="username" type="text" class="form-control" placeholder="Email" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input id="password" type="password" class="form-control" placeholder="Password" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- /.col -->
					<div class="col-12">
						<button id="signIn" type="button" class="btn btn-primary btn-block">Giriş</button>
					</div>
					<!-- /.col -->
				</div>
		</div>
		<!-- /.login-card-body -->
	</div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=base_url()?>assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>assets/admin/dist/js/adminlte.min.js"></script>

<script>
	function redirectToAdmin(){
		window.location.href = "<?=base_url()?>categories_admin/";
	}
	$("#signIn").on('click', function () {

			let validated = true;

			if( $("#username").val().length < 2  )
			{
				$("#username").css('border', '1px solid red');
				validated = false;
			}
			else
			{
				$("#username").css('border', '');
			}

			if( $("#password").val().length < 2  )
			{
				$("#password").css('border', '1px solid red');
				validated = false;
			}
			else
			{
				$("#password").css('border', '');
			}

			if( validated )
			{
				let username = $("#username").val();
				let password = $("#password").val();
				$.post( "<?=base_url()?>admin/sign_in",  { username: username, password: password })
					.done(function( data ) {
						 data = JSON.parse( data );
						 if( data.status )
						 {
							 swal('Yönləndirilir');
							 setTimeout( redirectToAdmin, 1000);
						 }
						 else
						 {
							 swal({
								 text: data.message,
								 icon: "warning",
								 dangerMode: true,
							 });
						 }
					});
			}


	});
</script>

</body>
</html>
