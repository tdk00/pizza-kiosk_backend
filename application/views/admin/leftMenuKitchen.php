<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="<?=base_url()?>admin/logout" class="nav-link">Çıxış</a>
		</li>
	</ul>

</nav>
<!-- /.navbar -->

<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?=base_url()?>categories_admin" class="brand-link">
		<img src="<?=base_url()?>assets/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">KingsPizza Kiosk</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
					 with font-awesome or any other icon font library -->
<!--				<li class="nav-item">-->
<!--					<a href="#" class="nav-link">-->
<!--						<i class="nav-icon fas fa-tachometer-alt"></i>-->
<!--						<p>-->
<!--							Dashboard-->
<!--							<i class="right fas fa-angle-left"></i>-->
<!--						</p>-->
<!--					</a>-->
<!--					<ul class="nav nav-treeview">-->
<!--						<li class="nav-item">-->
<!--							<a href="./index.html" class="nav-link">-->
<!--								<i class="far fa-circle nav-icon"></i>-->
<!--								<p>Dashboard v1</p>-->
<!--							</a>-->
<!--						</li>-->
<!--					</ul>-->
<!--				</li>-->

				<li class="nav-item">
					<a href="<?=base_url()?>kitchen_admin" class="nav-link">
						<i class="nav-icon fas fa-book"></i>
						<p>
							Sifarişlər
						</p>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>
