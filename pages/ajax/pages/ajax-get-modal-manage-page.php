<div class="modal-header">
	<h5 class="modal-title mt-0"><?=($action == 'create') ? 'New page' : 'Updating <i class="mdi mdi-arrow-right"></i>' . $page->title ?></h5>
	<button type="button" class="close" id="closePageManager" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">

	<!-- BASIC INFO -->
    <div class="card card-bordered">
        <div class="card-header">
            Base information
        </div>
        <div class="card-body">
            <form method="post" action="" id="form_manage_page_base">
                <input type="hidden" name="action" value="<?=$action?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <input type="hidden" name="comienzo" value="<?=$comienzo?>" />
                <input type="hidden" name="limite" value="<?=$limite?>" />
                <input type="hidden" name="pagina" value="<?=$pagina?>" />

                <div class="row">

					<!-- TITLE -->
					<div class="col-md-6 col-xs-12">
						<div class="form-group">
							<label>* Title (internal)</label>
							<input type="text" name="title" value="<?=(isset($page->title)) ? $page->title : '';?>" class="form-control" placeholder="Indicates the title" />
						</div>
					</div>

					<!-- MOD ID -->
					<div class="col-md-2 col-xs-12">
						<div class="form-group">
							<label>* Mod_ID <i class="mdi mdi-information-outline tooltipTitle" data-placement="top" data-title="This is an internal name that is used to load the correct page in DefaultController. By default 'pages' will works correctly"></i></label>
							<input type="text" name="mod_id" value="<?=(isset($page->mod_id)) ? $page->mod_id : 'pages';?>" class="form-control" placeholder="Indicates the mod_id" />
						</div>
					</div>

					<!-- ZONE -->
					<div class="col-md-4 col-xs-12">
						<div class="form-group">
							<label>* Zone <i class="mdi mdi-information-outline tooltipTitle" data-placement="top" data-title="Zone is used to agroup different pages. To get this pages grouped to list before."></i></label>
							<input type="text" name="zone" value="<?=(isset($page->zone)) ? $page->zone : '';?>" class="form-control" placeholder="Indicates the zone (to agroup)" />
						</div>
					</div>

					<!-- STATUS -->
					<div class="col-md-12">
						<div class="form-group mb-0px">
							<label>Visible?</label>
							<div class="mb-10px"></div>
							<input type="checkbox" id="visible" name="visible" switch="none" <?=(isset($page->visible) && $page->visible == '1') ? 'checked' : ''?>/>
							<label for="visible" data-on-label="Yes" data-off-label="No"></label>
						</div>
					</div>

					<div class="col-md-12 col-xs-12 text-right pt-20px">
						<a href="javascript:void(0)" onclick="ajaxManagePageBase()" class="btn btn-<?=($action == 'create') ? 'success' : 'info';?>"><?=($action == 'create') ? 'Create' : 'Update';?></a>
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
						<a class="nav-link <?=($lang->is_default == '1') ? 'active' : '';?>" data-toggle="tab" href="#tab-career-<?=$lang->slug?>" role="tab"><img src="<?=_DOMINIO_.$lang->icon?>" width="20px" align="absmiddle" class="mr-10px" /><?=$lang->name?></a>
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

						//page data
						if(!empty($page) && $id != '0'){
							$pageDataLanguage = $page->{'lang_'.$lang->slug};

							if(empty($pageDataLanguage))
								$action = 'create';
							else
								$action = 'update';
						}
						else{
							$action = 'create';
							$pageDataLanguage = new \stdClass;
						}

						?>
						<div class="tab-pane mt-20px <?=($lang->is_default == '1') ? 'active' : '';?>" id="tab-career-<?=$lang->slug?>" role="tabpanel">

							<form method="post" action="" id="form_manage_page_<?=$lang->slug?>">
								<input type="hidden" name="action" value="<?=$action?>" />
								<input type="hidden" name="id" value="<?=$id?>" />
								<input type="hidden" name="id_lang" value="<?=$lang->id?>" />
								<input type="hidden" name="lang_name" value="<?=$lang->name?>" />
								<input type="hidden" name="lang_slug" value="<?=$lang->slug?>" />
								<input type="hidden" name="comienzo" value="<?=$comienzo?>" />
								<input type="hidden" name="limite" value="<?=$limite?>" />
								<input type="hidden" name="pagina" value="<?=$pagina?>" />

								<div class="row">

									<!-- H1 -->
									<div class="col-md-7 col-xs-12">
										<div class="form-group">
											<label>H1</label>
											<input type="text" name="h1" value="<?=(isset($pageDataLanguage->h1)) ? $pageDataLanguage->h1 : ''?>" <?=($id == '0') ? 'disabled' : '';?> placeholder="H1 in <?=$lang->name?>" class="form-control" autocomplete="off" />
										</div>
									</div>

									<!-- SLUG -->
									<div class="col-md-5 col-xs-12">
										<div class="form-group">
											<label>* slug</label>
											<input type="text" name="slug" value="<?=(isset($pageDataLanguage->slug)) ? $pageDataLanguage->slug : ''?>" <?=($id == '0') ? 'disabled' : '';?> placeholder="Slug in <?=$lang->name?>" class="form-control" autocomplete="off" />
										</div>
									</div>

                                    <!-- CONTENT -->
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>Content</label>
                                            <textarea name="content" rows="6" class="form-control <?=($id != '0') ? 'wysihtml5' : '';?>" <?=($id == '0') ? 'disabled' : '';?> placeholder="Description in <?=$lang->name?>"><?=(isset($pageDataLanguage->content)) ? $pageDataLanguage->content : ''?></textarea>
										</div>
									</div>

                                    <hr />

                                    <!-- SEO TITLE -->
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>SEO Title</label>
                                            <input type="text" name="seo_title" value="<?=(isset($pageDataLanguage->seo_title)) ? $pageDataLanguage->seo_title : ''?>" <?=($id == '0') ? 'disabled' : '';?> placeholder="SEO Title in <?=$lang->name?>" class="form-control" autocomplete="off" />
										</div>
									</div>

                                    <!-- SEO DESCRIPTION -->
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>SEO Description</label>
                                            <input type="text" name="seo_description" value="<?=(isset($pageDataLanguage->seo_description)) ? $pageDataLanguage->seo_description : ''?>" <?=($id == '0') ? 'disabled' : '';?> placeholder="SEO Description in <?=$lang->name?>" class="form-control" autocomplete="off" />
										</div>
									</div>

								</div>
							</form>

							<div class="space20"></div>
							<hr />

							<div class="row mt-20px">
								<div class="col-md-6 col-xs-12">
									<a href="javascript:void(0)" onclick="$('#closePageManager').click();" class="btn btn-dark">Close</a>
								</div>
								<div class="col-md-6 col-xs-12 text-right">
									<?php
										if($id != '0'){
											?><a href="javascript:void(0)" onclick="ajaxManagePage('<?=$lang->slug?>')" class="btn btn-<?=($action == 'create') ? 'success' : 'info';?>"><?=($action == 'create') ? 'Create translation' : 'Update translation';?></a><?php
										}
										else{
											?><a href="javascript:void(0)" style="cursor: not-allowed; opacity: 0.4;" class="btn btn-default tooltipTitle" data-placement="top" data-title="Before creating translations, you must create the base information"><?=($action == 'create') ? 'Create translation' : 'Update translation';?></a><?php
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
