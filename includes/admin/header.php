<div class="topbar">
	<div class="topbar-left">
		<a href="<?=_DOMINIO_._ADMIN_;?>" class="logo">
			<span>
				<img src="<?=_ASSETS_?>img/logo/logo-dark.png" alt="logo" style="max-height: 90%; margin-top: 5px;" />
			</span>
		</a>
	</div>

	<nav class="navbar-custom custom-color-bg">

		<ul class="navbar-right d-flex list-inline float-right mb-0">
			<li class="dropdown notification-list">
				<div class="dropdown notification-list nav-pro-img show">
					<a class="dropdown-toggle nav-link arrow-none waves-effect nav-user waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="true">
						<i class="mdi mdi-account-circle noti-icon"></i> <span class="text-white"><?=$_SESSION['admin']->name?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right profile-dropdown" x-placement="bottom-end">
						<a class="dropdown-item text-danger" href="<?=_DOMINIO_.$_SESSION['lang']?>/" target="_blank"><i class="mdi mdi-power text-danger"></i> Visitar web</a>
						<a class="dropdown-item text-danger" href="<?=_DOMINIO_._ADMIN_;?>logout/"><i class="mdi mdi-power text-danger"></i> Salir</a>
					</div>
				</div>
			</li>
		</ul>

		<ul class="list-inline menu-left mb-0">
			<li class="float-left">
				<button class="button-menu-mobile open-left waves-effect waves-light custom-color-bg">
					<i class="fa fa-bars"></i>
				</button>
			</li>
		</ul>

	</nav>

</div>
