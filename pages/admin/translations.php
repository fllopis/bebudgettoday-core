<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="page-title"><i class="ion-ios7-world-outline"></i> Translations</h4>
                </div>
                <div class="col-md-6 text-right">
                	<a href="javascript:void(0)" data-toggle="modal" data-target=".gestion-new-translation" class="btn btn-info mr-10px"> <i class="mdi mdi-plus"></i> New translation</a>
                    <a href="<?=_DOMINIO_._ADMIN_?>languages/" class="btn btn-light"> <i class="ion-ios7-world-outline"></i> Manage Languages</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="page-content-wrapper">

	<div class="row">

		<!-- STATS BLOCKS -->
		<div class="col-12">
            <div class="row">
                <?php
                    if(count($languages) > 0){
                        foreach($languages as $key => $idioma){

                            if( $idioma->totalTraductionsDone > '0' )
                                $traductionPercent = round(($idioma->totalTraductionsDone * 100) / $idioma->totalTraductions);
                            else
                                $traductionPercent = '0';

                            ?>
                            <div class="col-12 col-sm-2 col-md-2 col-xl-2 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><img src="<?=_DOMINIO_.$idioma->icon?>" width="20" />&nbsp;&nbsp;<?=$idioma->name?></h6>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-muted">Traductions at <?=$traductionPercent?>%</p>
                                            <p class="text-muted">100%</p>
                                        </div>
                                        <div class="progress progress-md">
                                            <div class="progress-bar" style="background: <?=$idioma->colour?>; width: <?=$traductionPercent;?>%;" role="progressbar" aria-valuenow="<?=$traductionPercent?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>

		<div class="col-xs-12 col-md-12">
			<div class="card m-b-20">
				<div class="card-body">

					<div class="row">
						<div class="col-md-4 col-xs-12">
							<p class="pt-5px">Manage the translations of the platform in its different languages.</p>
						</div>
						<div class="col-md-8 text-right">

							<!-- FILTERS -->
							<form method="post" action="" name="form_filters" id="form_filters">
								<div class="row">
									
									<div class="col-md-12 col-xs-12">
										<div class="row">

											<div class="col-md-3"></div>

											<!-- SARCH -->
											<div class="col-md-3">
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-search"></i></span>
													</div>
													<input type="text" name="search" onkeyup="ajaxGetTraductionsFiltered('<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" class="form-control" placeholder="Search translation" />
												</div>
											</div>

											<!-- TRANSLATIONS FOR -->
											<div class="col-md-3">
												<div class="input-group mb-3">
													<select name="traduction_for" class="form-control select2" onchange="ajaxGetTraductionsFiltered('<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');">
					                                    <option value="all">All zones &nbsp;&nbsp;</option>
					                                    <?php
					                                        foreach($traduntionsFor as $key => $traduction){

					                                            if($traduction->traduction_for == '')
					                                                $name = "Home";
					                                            else
					                                                $name = ucfirst(str_replace(array("-", "_"), " " ,$traduction->traduction_for));

					                                            ?><option value="<?=$traduction->traduction_for?>"><?=$name?> &nbsp;&nbsp;</option><?php
					                                        }
					                                    ?>
					                                </select>
												</div>
											</div>

											<!-- LANGUAGE -->
											<div class="col-md-3">
												<div class="input-group mb-3">
													<select name="slug_language" class="form-control select2" onchange="ajaxGetTraductionsFiltered('<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');">
						                                <option value="">Language default &nbsp;&nbsp;</option>
						                                <?php
						                                    foreach($languages as $key => $language){
						                                        ?><option value="<?=$language->slug?>" <?=(!empty($slug_language) && $slug_language == $language->id) ? 'selected' : '';?>><?=$language->name?> &nbsp;&nbsp;</option><?php
						                                    }
						                                ?>
					                                </select>
												</div>
											</div>

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
							ajaxGetTraductionsFiltered('<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');
						});
					</script>

				</div>
			</div>
			

		</div>
	</div>

</div>
<!-- end page content-->

<!-- MODAL - MANAGE DATA -->
<div class="modal fade manage-translation" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="manageTranslation__content"></div>
	</div>
</div>

<!-- MODAL - ADD TRANSLATION -->
<div class="modal fade gestion-new-translation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">New translation</h5>
                <button type="button" id="closeAddTranslationModal" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" action="" name="form_new_traduction" id="form_new_traduction">
                    <input type="hidden" name="id" value="0" />
                    <input type="hidden" name="action" value="create" />
                    <input type="hidden" name="comienzo" value="<?=$comienzo?>" />
                    <input type="hidden" name="limite" value="<?=$limite?>" />
                    <input type="hidden" name="pagina" value="<?=$pagina?>" />

                    <div class="form-group">
                        <label class="mb-0">Language of translation</label>
                        <select class="form-control" name="id_language" style="border: 1px solid #ccc; pointer-events: none;" readonly>
                            <?php
                                foreach($languages as $key => $language){
                                    ?><option value="<?=$language->id?>" <?=($language->id == $default_language->id) ? 'selected' : '';?> ><?=$language->name?></option><?php
                                }
                            ?>
                        </select>
                        <small>Creation of the translation in the default language, you can add the rest of the translations from the list.</small>
                    </div>

                    <div class="form-group">
                        <label class="mb-0">Traduction for</label>
                        <input type="text" name="traduction_for" id="input_traduction_for" value="" class="form-control" placeholder="Ejemplo: home">
                    </div>

                    <div class="form-group">
                        <label class="mb-0">Shortcode</label>
                        <input type="text" name="shortcode" value="" id="input_shortcode" class="form-control" placeholder="Ejemplo: btn-create-traduction">
                    </div>

                    <div class="form-group">
                        <label class="mb-0">Traduction</label>
                        <textarea name="content" id="input_content" rows="3" class="form-control" placeholder="Specify the translation for this language"></textarea>
                    </div>

                </form>

                <div class="text-right">
                    <button type="button" class="btn btn-success" onclick="ajaxManageNewTranslation();">Create</button>
                </div>

            </div>
        </div>
    </div>
</div>