<?php
	$totalCols = 5;
?>
<div class="table-responsive">
	<table class="table mb-0">
		<thead class="thead-default">
			<tr>
				<th class="text-center" width="5%">Flag</th>
				<th width="30%">Name</th>
				<?php
					if(count($languages) > 0){
						foreach($languages as $key => $lang){
							$totalCols++;
							?><th class="text-center"><img src="<?=_DOMINIO_.$lang->icon?>" width="20px" /></th><?php
						}
					}
				?>
				<th class="text-center">Status</th>
				<th class="text-center">Default language</th>
				<th class="text-right">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if(count($languagesFiltered) > 0){
					foreach($languagesFiltered as $key => $language){

						?>
						<tr>
							<td class="text-center">
								<img src="<?=_DOMINIO_.$language->icon?>" width="40%" />
							</td>
							<td>
								<?=$language->name?>
							</td>
							<?php
								if(count($languages) > 0){
									foreach($languages as $key => $lang){
										if(isset($language->langs[$lang->slug]) && $language->langs[$lang->slug])
											$iconTranslated = '<i class="mdi mdi-check font-size-18 text-success"></i>';
										else
											$iconTranslated = '<i class="mdi mdi-close font-size-18 text-danger"></i>';

										?><td class="text-center"><?=$iconTranslated?></td><?php
									}
								}
							?>
							<td class="text-center">
								<?php
									if($language->status == 'active'){
										?><span class="badge badge-success font-size-13">Active</span><?php
									}
									else{
										?><span class="badge badge-warning font-size-13">Deactive</span><?php
									}
								?>
							</td>
							<td class="text-center">
								<?php
									if($language->is_default == '1'){
										?><i class="mdi mdi-check font-size-18 text-success"></i><?php
									}
								?>
							</td>
							<td class="text-right">

								<!-- ACTIONS ONLY DESKTOP -->
								<div class="only-desktop">
									<a href="javascript:void(0);" onclick="ajaxModalManageLanguage('<?=$language->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-language" class="btn btn-success tooltipTitle" data-placement="top" data-title="Edit language"><i class="mdi mdi-pencil"></i></a>
								</div>

								<!-- ACTIONS ONLY MOBILE -->
								<div class="only-mobile">
									<button id="btnGroupVerticalDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Actions
									</button>
									<div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
										<a href="javascript:void(0);" onclick="ajaxModalManageLanguage('<?=$language->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-language" class="dropdown-item"><i class="mdi mdi-pencil"></i> Editar idioma</a>
									</div>
								</div>
							</td>
						</tr>
						<?php
					}
				}
				else{
					?>
					<tr>
						<td colspan="<?=$totalCols?>" class="text-center"><i class="mdi mdi-alert"></i> No se han encontrado idiomas.</td>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>
</div>

<!-- PAGINADOR -->
<div class="row mt-4">
	<div class="col-md-12 text-right">
		<?php $this->app['tools']->getPaginador($pagina, $limite, 'lang', 'getLanguagesFiltered', 'ajaxGetLanguagesFiltered'); ?>
	</div>
</div>

<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
	$('.tooltipTitle').tooltip();
</script>