
<!DOCTYPE html>
<html>
<head>
	<title>Instalar Anelis Framework</title>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../assets/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/sweetalert/sweet-alert.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
	<style type="text/css">
		body{ background-color: #004a81; font-family: 'Ubuntu'; }
		h2{ margin: 0 0 15px 0; }
		hr{ width: 100%; border-color: #717070; float: left; }
		.head{ text-align: center; padding: 20px 0; justify-content: center; }
		.squareBg{ padding: 20px 0; background-color: white; border-radius: 8px; box-shadow: 0px 2px 2px; margin-bottom: 10px; }
		.squareBg div:not(.step){ margin-bottom: 10px; }
		.steps{ display: grid; grid-template-columns: 1fr 1fr 1fr; }
		.step{ text-align: center; border-right: 1px solid black; font-size: 16px; }
		.step:last-child{ border: none; }
		.step.step--active{ font-weight: bold; text-decoration: underline; }
		.material-switch{ height: 34px;  display: flex; align-items: center; }
		.material-switch > input[type="checkbox"]{ display: none; }
		.material-switch > label{ cursor: pointer; height: 0px; position: relative; width: 40px; }
		.material-switch > label::before{ background: rgb(224, 4, 4); box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5); border-radius: 8px; content: ''; height: 16px; margin-top: -8px; position:absolute;opacity: 0.8; transition: all 0.4s ease-in-out; width: 40px; }
		.material-switch > label::after{ background: rgb(255, 255, 255); border-radius: 16px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3); content: ''; height: 24px; left: -4px; margin-top: -8px; position: absolute; top: -4px; transition: all 0.3s ease-in-out; width: 24px; }
		.material-switch > input[type="checkbox"]:checked + label::before{ background: rgb(8, 169, 6); }
		.material-switch > input[type="checkbox"]:checked + label::after{ left: 20px; }
		.btn-primary{ background-color: #004a81; border-color: #122b40; }
		.btn-primary:hover, .btn-primary:focus, .btn-primary:active{ background-color: #00355d; border-color: #122b40; }
		#datos-desarrollo{ display: none; }
		#bd_check_result, #bd_check_result_dev{ display: none; margin-top: 10px; }
		.waitBlock{ padding: 20px; background: #D9EDF7 url('img/ajax-loader-small.gif') no-repeat 16px 12px; border: 1px solid #81CFE6; }
		.errorBlock{ background: url(img/pict_error.png) no-repeat scroll 15px 21px #FFEBE8; border: 1px solid #CC0000; padding: 20px 20px 20px 38px; }
		.okBlock{ padding: 20px 20px 20px 38px; background: #b7e2a7 url(img/bg-li-tabs-finished.png) no-repeat 15px 21px; border: 1px solid #85c10c; }
		.step-content__three__success{ display: none; }
	</style>
	<script type="text/javascript" src="../assets/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/sweetalert/sweet-alert.min.js"></script>
</head>
<body>
	<header>
		<div class="container">
			<div class="row head squareBg">
				<img src="img/anelis.png" />
			</div>
		</div>
	</header>
	<section>
		<div class="container">
			<div class="row steps squareBg">
				<div class="step step__one step--active">Paso 1. Introducir datos</div>
				<div class="step step__two">Paso 2. Instalación</div>
				<div class="step step__three">Paso 3. Fin</div>
			</div>
		</div>
	</section>
	<section id="step-content__one">
		<div class="container">
			<div class="row step-content__one squareBg">
				<div class="col-12">
					<h2>Datos principales</h2>
				</div>
				<div class="col-sm-6">
					<label for="nombre_proyecto">Nombre proyecto</label>
					<input type="text" name="nombre_proyecto" id="nombre_proyecto" class="form-control" value="Anelis Framework" />
				</div>
				<div class="col-sm-6">
					<label for="modo_debug">Modo desarrollo</label>
					<div class="material-switch">
						<input id="modo_debug" name="modo_debug" type="checkbox"/>
						<label for="modo_debug"></label>
					</div>
				</div>
				<hr />
				<div class="clearfix"></div>
				<div class="col-12">
					<h2>Datos administrador</h2>
				</div>
				<div class="col-sm-4">
					<label for="admin_nombre">Nombre administrador</label>
					<input type="text" name="admin_nombre" id="admin_nombre" class="form-control" value="david" />
				</div>
				<div class="col-sm-4">
					<label for="admin_email">Email administrador</label>
					<input type="text" name="admin_email" id="admin_email" class="form-control" value="d.barreiro@anelis.com" />
				</div>
				<div class="col-sm-4">
					<label for="admin_pass">Contraseña administrador</label>
					<input type="password" name="admin_pass" id="admin_pass" class="form-control" value="anet1002" />
				</div>
				<hr />
				<div class="clearfix"></div>
				<div class="col-12">
					<h2>Datos SMTP</h2>
				</div>
				<div class="col-sm-3">
					<label for="smtp_servidor">Servidor SMTP</label>
					<input type="text" name="smtp_servidor" id="smtp_servidor" class="form-control" value="mail.anelishost.es" />
				</div>
				<div class="col-sm-3">
					<label for="smtp_usuario">Usuario SMTP</label>
					<input type="text" name="smtp_usuario" id="smtp_usuario" class="form-control" value="noreply@anelis.com" />
				</div>
				<div class="col-sm-3">
					<label for="smtp_pass">Contraseña SMTP</label>
					<input type="password" name="smtp_pass" id="smtp_pass" class="form-control" value="Tc7gq_46woG2q$10" />
				</div>
				<div class="col-sm-3">
					<label for="smtp_puerto">Puerto SMTP</label>
					<input type="text" name="smtp_puerto" id="smtp_puerto" class="form-control" value="587" />
				</div>
				<hr />
				<div class="clearfix"></div>
				<div class="col-12">
					<h2>Dominio y base de datos - Producción</h2>
				</div>
				<div class="col-sm-4">
					<label for="dominio">Dominio (con https y www, si corresponde)</label>
					<input type="text" name="dominio" id="dominio" class="form-control" value="http://localhost/anelis_framework/" />
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-3">
					<label for="bd_servidor">Host base de datos</label>
					<input type="text" name="bd_servidor" id="bd_servidor" class="form-control" value="51.254.152.105" />
				</div>
				<div class="col-sm-3">
					<label for="bd_nombre">Nombre base de datos</label>
					<input type="text" name="bd_nombre" id="bd_nombre" class="form-control" value="anelis_framework_bd" />
				</div>
				<div class="col-sm-3">
					<label for="bd_usuario">Usuario base de datos</label>
					<input type="text" name="bd_usuario" id="bd_usuario" class="form-control" value="an_frame_usbd" />
				</div>
				<div class="col-sm-3">
					<label for="bd_password">Contraseña base de datos</label>
					<input type="password" name="bd_password" id="bd_password" class="form-control" value="l06&kaK003!Ppg9x" />
				</div>
				<div class="col-12">
					<input id="btn-test-bd" class="btn btn-primary" type="button" value="Comprobar conexión con base de datos">
					<p id="bd_check_result"></p>
				</div>
				<hr />
				<div class="clearfix"></div>
				<div id="datos-desarrollo" class="col-12">
					<div class="row">
						<div class="col-12">
							<h2>Dominio y base de datos - Desarrollo</h2>
						</div>
						<div class="col-sm-4">
							<label for="dominio_dev">Dominio (con https y www, si corresponde)</label>
							<input type="text" name="dominio_dev" id="dominio_dev" class="form-control" />
						</div>
						<div class="clearfix"></div>
						<div class="col-sm-3">
							<label for="bd_servidor_dev">Host base de datos</label>
							<input type="text" name="bd_servidor_dev" id="bd_servidor_dev" class="form-control" value="localhost" />
						</div>
						<div class="col-sm-3">
							<label for="bd_nombre_dev">Nombre base de datos</label>
							<input type="text" name="bd_nombre_dev" id="bd_nombre_dev" class="form-control" />
						</div>
						<div class="col-sm-3">
							<label for="bd_usuario_dev">Usuario base de datos</label>
							<input type="text" name="bd_usuario_dev" id="bd_usuario_dev" class="form-control" />
						</div>
						<div class="col-sm-3">
							<label for="bd_password_dev">Contraseña base de datos</label>
							<input type="password" name="bd_password_dev" id="bd_password_dev" class="form-control" />
						</div>
						<div class="col-12">
							<input id="btn-test-bd-dev" class="btn btn-primary" type="button" value="Comprobar conexión con base de datos">
							<p id="bd_check_result_dev"></p>
						</div>
						<hr />
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-12 text-right">
					<input id="btn-instalar" class="btn btn-primary" type="button" name="btn_instalar" value="Comenzar instalación">
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</section>

	<section id="step-content__two" style="display: none;">
		<div class="container">
			<div class="row step-content__two squareBg text-center">
				<img src="img/ajax-loader-small.gif"> Procesando
			</div>
		</div>
	</section>

	<section id="step-content__three" style="display: none;">
		<div class="container">
			<div class="row step-content__three squareBg">
				<div class="col-12">
					<p class="step-content__three__success okBlock"></p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</section>

	<script type="text/javascript">
		$('#modo_debug').change(function(){
			let checked = $(this).prop("checked");
			if( checked )
				$("#datos-desarrollo").slideDown('fast');
			else
				$("#datos-desarrollo").slideUp('fast');
		});

		$("#btn-instalar").click(function()
		{
			var formData = new FormData();
			formData.append("nombre_proyecto", $("#nombre_proyecto").val());
			formData.append("modo_debug",  $("#modo_debug").prop("checked"));
			formData.append("admin_nombre",  $("#admin_nombre").val());
			formData.append("admin_email",  $("#admin_email").val());
			formData.append("admin_pass",  $("#admin_pass").val());
			formData.append("smtp_servidor",  $("#smtp_servidor").val());
			formData.append("smtp_usuario",  $("#smtp_usuario").val());
			formData.append("smtp_pass",  $("#smtp_pass").val());
			formData.append("smtp_puerto",  $("#smtp_puerto").val());
			formData.append("dominio",  $("#dominio").val());
			formData.append("bd_servidor",  $("#bd_servidor").val());
			formData.append("bd_nombre",  $("#bd_nombre").val());
			formData.append("bd_usuario",  $("#bd_usuario").val());
			formData.append("bd_password",  $("#bd_password").val());
			formData.append("dominio_dev",  $("#dominio_dev").val());
			formData.append("bd_servidor_dev",  $("#bd_servidor_dev").val());
			formData.append("bd_nombre_dev",  $("#bd_nombre_dev").val());
			formData.append("bd_usuario_dev",  $("#bd_usuario_dev").val());
			formData.append("bd_password_dev",  $("#bd_password_dev").val());

			if( formData.get("nombre_proyecto") != '' )
			{
				if( formData.get("admin_nombre") != '' )
				{
					if( formData.get("admin_email") != '' )
					{
						if( formData.get("admin_pass") != '' )
						{
							if( formData.get("smtp_servidor") != '' )
							{
								if( formData.get("smtp_usuario") != '' )
								{
									if( formData.get("smtp_pass") != '' )
									{
										if( formData.get("smtp_puerto") != '' )
										{
											if( formData.get("dominio") != '' )
											{
												if( formData.get("bd_servidor") != '' )
												{
													if( formData.get("bd_nombre") != '' )
													{
														if( formData.get("bd_usuario") != '' )
														{
															if( formData.get("bd_password") != '' )
															{
																if( formData.get("modo_debug") == 'true' )
																{
																	if( formData.get("dominio_dev") != '' )
																	{
																		if( formData.get("bd_servidor_dev") != '' )
																		{
																			if( formData.get("bd_nombre_dev") != '' )
																			{
																				if( formData.get("bd_usuario_dev") != '' )
																				{
																					if( formData.get("bd_password_dev") != '' )
																					{
																						initInstall(formData);
																					}
																					else
																						sw('¡Error!', 'Debes rellenar el campo Contraseña base de datos del entorno de Desarrollo.', 'error', 'Cerrar');
																				}
																				else
																					sw('¡Error!', 'Debes rellenar el campo Usuario base de datos del entorno de Desarrollo.', 'error', 'Cerrar');
																			}
																			else
																				sw('¡Error!', 'Debes rellenar el campo Nombre base de datos del entorno de Desarrollo.', 'error', 'Cerrar');
																		}
																		else
																			sw('¡Error!', 'Debes rellenar el campo Host base de datos del entorno de Desarrollo.', 'error', 'Cerrar');
																	}
																	else
																		sw('¡Error!', 'Debes rellenar el campo Dominio del entorno de Desarrollo.', 'error', 'Cerrar');
																}
																else
																{
																	initInstall(formData);
																}
															}
															else
																sw('¡Error!', 'Debes rellenar el campo Contraseña base de datos.', 'error', 'Cerrar');
														}
														else
															sw('¡Error!', 'Debes rellenar el campo Usuario base de datos.', 'error', 'Cerrar');
													}
													else
														sw('¡Error!', 'Debes rellenar el campo Nombre base de datos.', 'error', 'Cerrar');
												}
												else
													sw('¡Error!', 'Debes rellenar el campo Host base de datos.', 'error', 'Cerrar');
											}
											else
												sw('¡Error!', 'Debes rellenar el campo Dominio.', 'error', 'Cerrar');
										}
										else
											sw('¡Error!', 'Debes rellenar el campo Puerto SMTP.', 'error', 'Cerrar');
									}
									else
										sw('¡Error!', 'Debes rellenar el campo Contraseña SMTP.', 'error', 'Cerrar');
								}
								else
									sw('¡Error!', 'Debes rellenar el campo Usuario SMTP.', 'error', 'Cerrar');
							}
							else
								sw('¡Error!', 'Debes rellenar el campo Servidor SMTP.', 'error', 'Cerrar');
						}
						else
							sw('¡Error!', 'Debes rellenar la contraseña del administrador.', 'error', 'Cerrar');
					}
					else
						sw('¡Error!', 'Debes rellenar el e-mail del administrador.', 'error', 'Cerrar');
				}
				else
					sw('¡Error!', 'Debes rellenar el nombre del administrador.', 'error', 'Cerrar');
			}
			else
				sw('¡Error!', 'Debes rellenar el nombre del proyecto.', 'error', 'Cerrar');
		});

		$('#btn-test-bd').click(function()
		{
			$("#bd_check_result")
				.removeClass('errorBlock')
				.removeClass('okBlock')
				.addClass('waitBlock')
				.html('<br/>')
				.slideDown('slow');
			
			let servidor = $("#bd_servidor").val();
			let nombre = $("#bd_nombre").val();
			let usuario = $("#bd_usuario").val();
			let password = $("#bd_password").val();

			if( servidor != '' && nombre != '' && usuario != '' && password != '' )
			{
				$.ajax({
					url: 'ajax.php',
					data: {
						'action': 'check_bd',
						'bd_servidor': servidor,
						'bd_nombre': nombre,
						'bd_usuario': usuario,
						'bd_password': password,
					},
					dataType: 'json',
					cache: false,
					success: function(json)
					{
						$("#bd_check_result")
							.addClass((json.type == 'success') ? 'okBlock' : 'errorBlock')
							.removeClass('waitBlock')
							.removeClass((json.type == 'success') ? 'errorBlock' : 'okBlock')
							.html((json.type == 'success') ? json.success : json.error);
					},
					error: function(error)
					{
						console.log(error);
					}
				});
			}
			else
			{
				$("#bd_check_result")
					.addClass('errorBlock')
					.removeClass('waitBlock')
					.removeClass('okBlock')
					.html("Debes rellenar todos los datos");
			}
		});

		$('#btn-test-bd-dev').click(async function()
		{
			$("#bd_check_result_dev")
				.removeClass('errorBlock')
				.removeClass('okBlock')
				.addClass('waitBlock')
				.html('<br/>')
				.slideDown('slow');
			
			let servidor = $("#bd_servidor_dev").val();
			let nombre = $("#bd_nombre_dev").val();
			let usuario = $("#bd_usuario_dev").val();
			let password = $("#bd_password_dev").val();

			if( servidor != '' && nombre != '' && usuario != '' && password != '' )
			{
				$.ajax({
					url: 'ajax.php',
					data: {
						'action': 'check_bd',
						'bd_servidor': servidor,
						'bd_nombre': nombre,
						'bd_usuario': usuario,
						'bd_password': password,
					},
					dataType: 'json',
					cache: false,
					success: function(json)
					{
						$("#bd_check_result_dev")
							.addClass((json.type == 'success') ? 'okBlock' : 'errorBlock')
							.removeClass('waitBlock')
							.removeClass((json.type == 'success') ? 'errorBlock' : 'okBlock')
							.html((json.type == 'success') ? json.success : json.error);
					},
					error: function(error)
					{
						console.log(error);
					}
				});
			}
			else
			{
				$("#bd_check_result_dev")
					.addClass('errorBlock')
					.removeClass('waitBlock')
					.removeClass('okBlock')
					.html("Debes rellenar todos los datos");
			}
		});

		function initInstall(formData)
		{
			formData.append("action", "install");
			$(".step").removeClass("step--active");
			$(".step__two").addClass("step--active");
			$("#step-content__one").hide();
			$("#step-content__two").show();

			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data)
				{
					console.log(data);
					data = JSON.parse(data);
					if( data.type == 'success' )
					{
						//Todo correcto = paso 3
						$(".step").removeClass("step--active");
						$(".step__three").addClass("step--active");
						$("#step-content__two").hide();
						$("#step-content__three").show();
						$(".step-content__three__success").text(data.success).show();
					}
					else
					{
						//Error vuelta a paso 1
						$(".step").removeClass("step--active");
						$(".step__one").addClass("step--active");
						$("#step-content__one").show();
						$("#step-content__two").hide();
						sw('¡Error!', data.error, 'error', 'Cerrar');
					}
				}
			});
		}

		function sw(title, message, type, button)
		{
			swal({
				title: title,
				text: message,
				type: type,
				html: true,
				confirmButtonText: button,
				confirmButtonColor: '#b70e0e',
				closeOnConfirm: true
			});
		}
	</script>
</body>
</html>