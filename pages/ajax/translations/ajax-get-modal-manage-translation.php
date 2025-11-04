<div class="modal-header">
	<h5 class="modal-title mt-0"><?=($action == 'create') ? 'New translation' : 'Updating translation'; ?></h5>
	<button type="button" class="close" id="closeTranslationGestion" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">

	<div class="alert alert-info-custom">If there is a word between %, e.g. <strong>%name%</strong> this word must be kept as it is a string that will be translated at the programming level by the corresponding field of the zone.</div>
	
	<!-- TAB LANGUAGES -->
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

						//CONTENT FOR EACH LANGUAGE
						if(!empty($translations)){
							$datos_tranduction = $translations->{'lang_'.$lang->slug};

							if(empty($datos_tranduction))
								$action = 'create';
							else
								$action = 'update';
						}
						else{
							$action = 'create';
							$datos_tranduction = new \stdClass;
						}

						?>
						<div class="tab-pane mt-20px <?=($lang->is_default == '1') ? 'active' : '';?>" id="tab-block-<?=$lang->slug?>" role="tabpanel">

							<form method="post" action="" id="form_gestion_translation_<?=$lang->slug?>">
								<input type="hidden" name="id" value="<?=$id?>" />
								<input type="hidden" name="action" value="<?=$action?>" />
								<input type="hidden" name="traduction_for" value="<?=(isset($translation->traduction_for)) ? $translation->traduction_for : ''?>" />
								<input type="hidden" name="shortcode" value="<?=(isset($translation->shortcode)) ? $translation->shortcode : ''?>" />
								<input type="hidden" name="id_lang" value="<?=$lang->id?>" />
								<input type="hidden" name="lang_name" value="<?=$lang->name?>" />
								<input type="hidden" name="lang_slug" value="<?=$lang->slug?>" />
								<input type="hidden" name="comienzo" value="<?=$comienzo?>" />
								<input type="hidden" name="limite" value="<?=$limite?>" />
								<input type="hidden" name="pagina" value="<?=$pagina?>" />

								<div class="row">

									<!-- TITLE -->
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>Content</label>
											<textarea name="content" rows="8" placeholder="Translation content for <?=$lang->name?>" class="form-control" autocomplete="off"><?=(isset($datos_tranduction->content)) ? $datos_tranduction->content : ''?></textarea>
										</div>
									</div>

								</div>
							</form>

							<div class="space20"></div>
							<hr />

							<div class="row mt-20px">
								<div class="col-md-6 col-xs-12">
									<a href="javascript:void(0)" onclick="$('#closeTranslationGestion').click();" class="btn btn-dark">Close</a>
								</div>
								<div class="col-md-6 col-xs-12 text-right">
									<a href="javascript:void(0)" onclick="ajaxManageTranslation('<?=$lang->slug?>')" class="btn btn-<?=($action == 'create') ? 'success' : 'info';?>"><?=($action == 'create') ? 'Create' : 'Update';?></a>
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

<script type="text/javascript">
	$("form").submit(function(e){
        e.preventDefault();
    });

    $(document).ready(function(){
		
		//Selectables
		$('.select2').select2();
		$(".selectpicker").selectpicker();

	});
</script>
