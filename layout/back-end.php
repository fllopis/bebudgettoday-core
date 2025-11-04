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

    <?php $this->app['metas']->getMetas();?>

    <?php $this->app['tools']->loadBootstrap('css');?>
    <?php include(_INCLUDES_._ADMIN_.'stylesheets.php'); ?>
    <link href="<?=_ASSETS_._ADMIN_;?>bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=_ASSETS_._ADMIN_;?>bootstrap-md-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?=_ASSETS_._ADMIN_;?>select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=_ASSETS_._ADMIN_;?>sweetalert2.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?=_ASSETS_._ADMIN_;?>metismenu.min.css" rel="stylesheet" type="text/css" />

	<link href="<?=_ASSETS_._ADMIN_;?>magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

    <link href="<?=_ASSETS_._ADMIN_;?>icons.css" rel="stylesheet" type="text/css" />
    <link href="http://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=_ASSETS_._ADMIN_;?>style.css?c=<?=time()?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.0-beta/css/bootstrap-select.min.css">

	<!-- SLIDE CSS - SPLIDE -->
    <link rel="stylesheet" href="<?=_ASSETS_._ADMIN_;?>splide/css/splide.min.css?c=<?=time();?>">

    <link href="<?=_ASSETS_._ADMIN_;?>croppie.css?c=<?=time()?>" rel="stylesheet" type="text/css" />
    <link href="<?=_ASSETS_._ADMIN_;?>custom.css?c=<?=time()?>" rel="stylesheet" type="text/css" />
    <style>
    	<?php $mainColor = '#8750f7';?>
    	.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before{ background-color: <?=$mainColor;?> !important; }
		.bg-primary{ background-color: <?=$mainColor;?> !important; }
		.text-primary{ color: <?=$mainColor;?> !important; }
		.badge-primary{ background-color: <?=$mainColor;?> !important; }
		.submenu li a:hover{ color: <?=$mainColor;?> !important; }
		.submenu li.active > a{ color: <?=$mainColor;?> !important; }
		.navbar-custom{ background-color: <?=$mainColor;?> !important; }
		.logo span span{ color: <?=$mainColor;?> !important; }
		.button-menu-mobile{ background-color: <?=$mainColor;?> !important; }
		#sidebar-menu > ul > li > a:hover, #sidebar-menu > ul > li > a:focus, #sidebar-menu > ul > li > a:active{ color: <?=$mainColor;?> !important; }
		#sidebar-menu > ul > li > a.active{ color: <?=$mainColor;?> !important; }
		.enlarged #wrapper .left.side-menu #sidebar-menu > ul > li > a:hover, .enlarged #wrapper .left.side-menu #sidebar-menu > ul > li > a:active, .enlarged #wrapper .left.side-menu #sidebar-menu > ul > li > a:focus{ color: <?=$mainColor;?> !important; }
		.enlarged #wrapper .left.side-menu #sidebar-menu ul > li:hover > a{ color: <?=$mainColor;?> !important; }
		.enlarged #wrapper .left.side-menu #sidebar-menu ul ul li.active a{ color: <?=$mainColor;?> !important; }
		.enlarged #wrapper .topbar .topbar-left .logo i{ color: <?=$mainColor;?> !important; }
		.topbar-left-dark{ background: <?=$mainColor;?> !important; }
		.btn-primary{ background-color: <?=$mainColor;?> !important; border: 1px solid <?=$mainColor;?> !important; }
		.btn-link:hover{ color: <?=$mainColor;?> !important; }
		.btn-outline-primary{ color: <?=$mainColor;?> !important; border-color: <?=$mainColor;?> !important; }
		.message-list li.active, .message-list li.active:hover{ -webkit-box-shadow: inset 3px 0 0 <?=$mainColor;?> !important; box-shadow: inset 3px 0 0 <?=$mainColor;?> !important; }
		.alert-primary{ color: <?=$mainColor;?> !important; }
		.icon-demo-content .col-md-4:hover i{ color: <?=$mainColor;?> !important; }
		.page-item.active .page-link{ background-color: <?=$mainColor;?> !important; border-color: <?=$mainColor;?> !important; }
		.progress-bar{ background-color: <?=$mainColor;?> !important; }
		.swal2-icon.swal2-question{ color: <?=$mainColor;?> !important; border-color: <?=$mainColor;?> !important; }
		.swal2-modal .swal2-file:focus, .swal2-modal .swal2-input:focus, .swal2-modal .swal2-textarea:focus{ border: 2px solid <?=$mainColor;?> !important; }
		.nav-tabs-custom > li > a::after{ background: <?=$mainColor;?> !important; }
		.nav-tabs-custom > li > a.active{ color: <?=$mainColor;?> !important; }
		.nav-pills .nav-link.active, .nav-pills .show > .nav-link{ background: <?=$mainColor;?> !important; }
		.form-control:focus{ border-color: <?=$mainColor;?> !important; }
		.custom-control-input:checked ~ .custom-control-indicator{ background-color: <?=$mainColor;?> !important; }
		.custom-control-input:focus ~ .custom-control-indicator{ -webkit-box-shadow: 0 0 0 1px #ffffff, 0 0 0 3px <?=$mainColor;?> !important; box-shadow: 0 0 0 1px #ffffff, 0 0 0 3px <?=$mainColor;?> !important; }
		.datepicker table tr td.active, .datepicker table tr td.active:hover, .datepicker table tr td.active.disabled, .datepicker table tr td.active.disabled:hover, .datepicker table tr td.today, .datepicker table tr td.today.disabled, .datepicker table tr td.today.disabled:hover, .datepicker table tr td.today:hover, .datepicker table tr td.selected, .datepicker table tr td.selected.disabled, .datepicker table tr td.selected.disabled:hover, .datepicker table tr td.selected:hover{ background-color: <?=$mainColor;?> !important; }
		.select2-container--default .select2-results__option--highlighted[aria-selected]{ background-color: <?=$mainColor;?> !important; }
		input[switch]:checked + label{ background-color: <?=$mainColor;?> !important; }
		input[switch="primary"]:checked + label{ background-color: <?=$mainColor;?> !important; }
		.mce-menu-item:hover, .mce-menu-item.mce-selected, .mce-menu-item:focus{ background-color: <?=$mainColor;?> !important; }
		.fc-state-active, .fc-state-down{ background-color: <?=$mainColor;?> !important; border-color: <?=$mainColor;?> !important; }
		.fc-event{ background-color: <?=$mainColor;?> !important; }
		table.focus-on tbody tr.focused th{ background-color: <?=$mainColor;?> !important; }
		table.focus-on tbody tr.focused td{ background-color: <?=$mainColor;?> !important; }
		.table-rep-plugin .checkbox-row input[type="checkbox"]:checked + label::before{ background-color: <?=$mainColor;?> !important; border-color: <?=$mainColor;?> !important; }
		.gmaps-overlay{ background: <?=$mainColor;?> !important; }
		.gmaps-overlay_arrow.above{ border-top: 16px solid <?=$mainColor;?> !important; }
		.gmaps-overlay_arrow.below{ border-bottom: 16px solid <?=$mainColor;?> !important; }
		.ct-chart .ct-series.ct-series-a .ct-bar, .ct-chart .ct-series.ct-series-a .ct-line, .ct-chart .ct-series.ct-series-a .ct-point, .ct-chart .ct-series.ct-series-a .ct-slice-donut{ stroke: <?=$mainColor;?> !important; }
		.ct-series-a .ct-area, .ct-series-a .ct-slice-pie{ fill: <?=$mainColor;?> !important; }
		.recent-activity-tab .nav-item::before{ background: <?=$mainColor;?> !important; }
		.recent-activity-tab .nav-item .nav-link.active:before{ background: <?=$mainColor;?> !important; }
		.activity-feed .feed-item::after{ border: 4px solid <?=$mainColor;?> !important; }
		.cd-timeline-img{ background-color: <?=$mainColor;?> !important; }
		.cd-timeline-content .cd-read-more{ background: <?=$mainColor;?> !important; }
    </style>
    <script type="text/javascript">
    	const dominio = "<?=_DOMINIO_;?>";
    </script>
    <?php include(_INCLUDES_._ADMIN_.'javascript_top.php'); ?>
</head>
<body class="">
    <div id="wrapper">
        <header id="header">
            <?php include(_INCLUDES_._ADMIN_.'header.php'); ?>
        </header>

        <?php include(_INCLUDES_._ADMIN_.'left_column.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <?php $this->app['render']->getAdminPage();?>
                </div>
            </div>

            <footer class="footer">
                <?php include(_INCLUDES_._ADMIN_.'footer.php'); ?>
            </footer>
        </div>
    </div>

    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>metismenu.min.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>jquery.slimscroll.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>waves.min.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>sweetalert2.min.js"></script>

    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap-md-datetimepicker/js/moment-with-locales.min.js"></script>
	<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap-md-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>select2/js/select2.min.js"></script>
	<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>

	<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>magnific-popup/jquery.magnific-popup.min.js"></script>
	<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>lightbox/lightbox.init.js"></script>

    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>tinymce/langs/es.js"></script>
    <script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>app.js"></script>
	
	<!-- SLIDER -->
    <script src="<?=_ASSETS_._ADMIN_;?>splide/js/splide.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.0-beta/js/bootstrap-select.min.js"></script>
	<?php include(_INCLUDES_._ADMIN_.'javascript_bottom.php'); ?>

	<script type="text/javascript">
		$(document).ready(function(){

			//Check resolution to close menu
			if ($(window).width() <= 767) {
				$('body').addClass('enlarged');
			} else {
				$('body').removeClass('enlarged');
			}

			// Select2
			$(".select2").select2();
			$(".selectpicker").selectpicker();

			//Loading splide carousel project images
            if(($("#carousel-project-gallery").length > 0)){
                new Splide( '#carousel-project-gallery', {
                    type: 'slide',
                    perPage: 3,
                    perMove: 1,
                    drag: 'free',
                    pagination: false,
                    arrows: true,
					lazyLoad: 'nearby',
					autoplay: true,
                    interval: 5000,
					pauseOnHover: true,
                    rewind: true,
                    breakpoints: {
                        600: {
                            perPage: 3,
                        }
                    },
                    padding: {
                        right: '0',
                        left : '0',
                    },
                } ).mount();
            }

			//Detectamos posibles editores de texto
			if($(".wysihtml5").length > 0){
				tinymce.init({
					selector: "textarea.wysihtml5",
					setup: function (editor) {
				        editor.on('change', function () {
				            editor.save();
				        });
				    },
					theme: "modern",
					height:300,
					plugins: [
						"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
						"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
						"save table contextmenu directionality emoticons template paste textcolor"
					],
					toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l	  ink image | print preview media fullpage | forecolor backcolor emoticons",
					style_formats: [
						{title: 'Bold text', inline: 'b'},
						{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
						{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
						{title: 'Example 1', inline: 'span', classes: 'example1'},
						{title: 'Example 2', inline: 'span', classes: 'example2'},
						{title: 'Table styles'},
						{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
					]
				});
			}
		});
	</script>

</body>
</html>
