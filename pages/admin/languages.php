<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="row">
				<div class="col-md-6">
					<h4 class="page-title"><i class="ion-ios7-world-outline"></i> Languages</h4>
				</div>
				<div class="col-md-6 text-right">
					<a href="<?=_DOMINIO_._ADMIN_?>translations/" class="btn btn-light mr-10px"> <i class="mdi mdi-arrow-left"></i> Back</a>
					<a href="javascript:void(0)" onclick="ajaxModalManageLanguage('0', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-language" class="btn btn-info"><i class="mdi mdi-plus"></i> New language</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end row -->

<div class="page-content-wrapper">

	<div class="row">
		<div class="col-xs-12 col-md-12">

			<div class="card m-b-20">
				<div class="card-body">

					<div class="row">
						<div class="col-md-6">
							<p>Manage the different languages available on the web.</p>
						</div>
						<div class="col-md-6 text-right">
							<form method="post" action="" name="form_filters" id="form_filters">
								<div class="row">
									<div class="col-md-12 col-xs-12">
										<div class="row">
											<div class="col-md-6"></div>
											<div class="col-md-6"></div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div id="loadingContent" class="row">
						<div class="col-md-12 text-center">
							<img src="<?=_DOMINIO_?>images/loading-v2.svg" width="150" />
						</div>
					</div>

					<!-- CONTENIDO DE LA TABLA -->
					<div id="table_content"></div>

					<script type="text/javascript">
						$(document).ready(function(){
							ajaxGetLanguagesFiltered('<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');
						});
					</script>

				</div>
			</div>
			

		</div>
	</div>

</div>
<!-- end page content-->

<!-- MODAL TO MANAGE LANGUAGE -->
<div class="modal fade manage-language" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="manageLanguage__content"></div>
	</div>
</div>