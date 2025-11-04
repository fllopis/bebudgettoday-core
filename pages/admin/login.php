<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<link rel="apple-touch-icon" href="<?=_DOMINIO_?>assets/img/icons/favicon/apple-touch-icon.png" />
        <link rel="shortcut icon" type="image/png" href="<?=_DOMINIO_?>assets/img/icons/favicon/favicon.ico" />
        <link rel="icon" type="image/png" sizes="32x32" href="<?=_DOMINIO_?>assets/img/icons/favicon/favicon.ico">
        <link rel="icon" type="image/png" sizes="16x16" href="<?=_DOMINIO_?>assets/img/icons/favicon/favicon.ico">
		<title>💻 GamifymeAPP Administration</title>
		<?php $this->app['tools']->loadBootstrap('css');?>
		<link rel="stylesheet" type="text/css" href="<?=_ASSETS_._ADMIN_;?>metismenu.min.css">
		<link rel="stylesheet" type="text/css" href="<?=_ASSETS_._ADMIN_;?>icons.css">
		<link rel="stylesheet" type="text/css" href="<?=_ASSETS_._ADMIN_;?>style.css">
		<link rel="stylesheet" type="text/css" href="<?=_ASSETS_._ADMIN_;?>custom.css">
		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>jquery/jquery.min.js"></script>

		<script language="javascript">
			var dominio = '<?=_DOMINIO_?>';
            // var reCAPTCHA = 'API_HERE';
        </script>

		<!-- GOOGLE reCAPTCHA -->
        <!-- <script src="https://www.google.com/recaptcha/api.js?render=API_HERE"></script> -->

	</head>

	<body>

		<!-- Background -->
		<div class="account-pages"></div>
		<!-- Begin page -->
		<div class="wrapper-page">

			<div class="card">
				<div class="card-body">

					<h3 class="text-center m-0">
						<img src="<?=_ASSETS_?>img/logo/logo-dark.png" alt="logo" />
					</h3>

					<div class="p-3" style="padding-top: 0px !important;">

						<!-- ERROR MESSAGE -->
						<div class="alert alert-danger bg-danger text-white error" role="alert" style="display:none;">
							<i class="mdi mdi-alert"></i> <span></span>
						</div>	

						<form class="form-horizontal m-t-10" method="post" action="" name="form_login" id="form_login">
							<div id="reCAPTCHA-G"></div>

							<div class="form-group">
								<label for="user">User</label>
								<input type="text" class="form-control" id="user" value="" placeholder="Indicates your username..." name="user" autocomplete="off" />
							</div>

							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" placeholder="Write your password..." name="password" autocomplete="off" />
							</div>
							
							<div class="row">
								<div class="loading-form text-right">
									
								</div>
								<div class="col-12 text-right">
									<img src="<?=_DOMINIO_?>images/loading-dark.svg" class="loading-form" height="34px" style="display: none;" />
									<a href="javascript:void(0)" class="btn btn-primary btn-login bg-primary trans-05 w-md waves-effect waves-light">Send</a>
								</div>
							</div>

						</form>
					</div>

				</div>
			</div>

		</div>

		<!-- END wrapper -->
			

		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>metismenu.min.js"></script>
		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>jquery.slimscroll.js"></script>
		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>waves.min.js"></script>
		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>sweetalert2.min.js"></script>

		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>app.js"></script>

		<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>funks.js"></script>
	</body>

</html>