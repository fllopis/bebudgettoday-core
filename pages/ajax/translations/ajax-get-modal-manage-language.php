<div class="modal-header">
	<h5 class="modal-title mt-0"><?=($action == 'create') ? 'New language' : 'Updating language'; ?></h5>
	<button type="button" class="close" id="closeLanguageGestion" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">

	<div class="card card-bordered">
		<div class="card-header">
			Basic language information
		</div>
		<div class="card-body">
			<form method="post" action="" id="form_manage_language_base">
				<input type="hidden" name="action" value="<?=$action?>" />
				<input type="hidden" name="id" value="<?=$id?>" />
				<input type="hidden" name="comienzo" value="<?=$comienzo?>" />
				<input type="hidden" name="limite" value="<?=$limite?>" />
				<input type="hidden" name="pagina" value="<?=$pagina?>" />

				<div class="row">
					<div class="col-md-8 offset-md-2 text-center">
						<?php
							if(isset($language->icon) && $language->icon != ''){
								?>
								<img src="<?=_DOMINIO_.$language->icon?>" width="70px" />
								<div class="space10"></div>
								<?php
							}
						?>
					</div>

					<!-- ICON -->
					<div class="col-md-6 col-xs-12">
						<!-- NEW IMAGE -->
						<label>Icon / Flag <small>(128x128)</small>. Look for it at <a href="https://www.iconfinder.com/icons/2634423/ensign_flag_nation_spain_icon" class="text-info" target="_blank">here</a> and download it at 128px.</label>
						<input type="file" name="image" class="filestyle" data-buttonname="btn-secondary" />
					</div>

					<!-- SLUG -->
					<div class="col-md-6 col-xs-12">
						<div class="form-group">
							<label>* Slug</label>
							<input type="text" name="slug" value="<?=(isset($language->slug)) ? $language->slug : '';?>" class="form-control" placeholder="Indicates the language slug" />
						</div>
					</div>

					<!-- STATUS -->
					<div class="col-md-6">
						<div class="form-group mb-0px">
							<label>Enable?</label>
							<br />
							<input type="checkbox" id="status" name="status" switch="none" <?=(isset($language->status) && $language->status == 'active') ? 'checked' : ''?>/>
							<label for="status" data-on-label="Yes" data-off-label="No"></label>
						</div>
					</div>

					<!-- IS_DEFAULT -->
					<div class="col-md-6">
						<div class="form-group mb-0px">
							<label>This is the default language?</label>
							<br />
							<?php
								if($id != '0'){
									?>
										<input type="checkbox" id="is_default" name="is_default" switch="none" <?=(isset($language->is_default) && $language->is_default == '1') ? 'checked' : ''?>/>
										<label for="is_default" data-on-label="Yes" data-off-label="No"></label>
									<?php
								}
								else{
									?><small class="text-warning">It can be set as “default” once it is created.</small><?php
								}
							?>
						</div>
					</div>

					<div class="col-md-12 col-xs-12 text-right pt-20px">
						<a href="javascript:void(0)" onclick="ajaxManageLanguageBase()" class="btn btn-<?=($action == 'create') ? 'success' : 'info';?>"><?=($action == 'create') ? 'Create' : 'Update';?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- TAB OF LANGUAGES -->
	<ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
		<?php
			if(count($languages) > 0){
				foreach($languages as $key => $lang){
					?>
					<li class="nav-item waves-effect waves-light">
						<a class="nav-link <?=($lang->is_default == '1') ? 'active' : '';?>" data-toggle="tab" href="#tab-block-<?=$lang->slug?>" role="tab"><img src="<?=_DOMINIO_.$lang->icon?>" width="20px" align="absmiddle" class="mr-10px" /><?=$lang->name?></a>
					</li>
					<?php
				}
			}
		?>
	</ul>

	<div class="row">
		<div class="col-md-12">
			<div class="tab-content">
				<?php
				if(count($languages) > 0){
					foreach($languages as $key => $lang){

						//Languages data block
						if(!empty($language) && $id != '0'){
							$datos_language = $language->{'lang_'.$lang->slug};

							if(empty($datos_language))
								$action = 'create';
							else
								$action = 'update';
						}
						else{
							$action = 'create';
							$datos_language = new \stdClass;
						}

						?>
						<div class="tab-pane mt-20px <?=($lang->is_default == '1') ? 'active' : '';?>" id="tab-block-<?=$lang->slug?>" role="tabpanel">

							<form method="post" action="" id="form_manage_language_<?=$lang->slug?>">
								<input type="hidden" name="action" value="<?=$action?>" />
								<input type="hidden" name="id" value="<?=$id?>" />
								<input type="hidden" name="id_lang" value="<?=$lang->id?>" />
								<input type="hidden" name="lang_name" value="<?=$lang->name?>" />
								<input type="hidden" name="lang_slug" value="<?=$lang->slug?>" />
								<input type="hidden" name="comienzo" value="<?=$comienzo?>" />
								<input type="hidden" name="limite" value="<?=$limite?>" />
								<input type="hidden" name="pagina" value="<?=$pagina?>" />

								<div class="row">

									<!-- NAME -->
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>* Name</label>
											<input type="text" name="name" value="<?=(isset($datos_language->name)) ? $datos_language->name : ''?>" placeholder="Name of the language in <?=$lang->name?>" class="form-control" autocomplete="off" />
										</div>
									</div>

								</div>
							</form>

							<div class="space20"></div>
							<hr />

							<div class="row mt-20px">
								<div class="col-md-6 col-xs-12">
									<a href="javascript:void(0)" onclick="$('#closeLanguageGestion').click();" class="btn btn-dark">Close</a>
								</div>
								<div class="col-md-6 col-xs-12 text-right">
									<?php
										if($id != '0'){
											?><a href="javascript:void(0)" onclick="ajaxManageLanguage('<?=$lang->slug?>')" class="btn btn-<?=($action == 'create') ? 'success' : 'info';?>"><?=($action == 'create') ? 'Create translation' : 'Update translation';?></a><?php
										}
										else{
											?><a href="javascript:void(0)" style="cursor: not-allowed; opacity: 0.4;" class="btn btn-default tooltipTitle" data-placement="top" data-title="Before creating translations you must fill in the basic language information."><?=($action == 'create') ? 'Create translation' : 'Update translation';?></a><?php
										}
									?>
								</div>
							</div>
						</div>

						<?php
					}
				}
				?>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript" src="<?=_ASSETS_._ADMIN_;?>bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>

<script type="text/javascript">
	$("form").submit(function(e){
        e.preventDefault();
    });

    $(document).ready(function(){
		
		//Selectables
		$('.select2').select2();
		$(".selectpicker").selectpicker();

		$('[data-toggle="tooltip"]').tooltip();
		$('.tooltipTitle').tooltip();

	});
</script>
