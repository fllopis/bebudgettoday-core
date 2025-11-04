<div class="table-responsive">
	<table class="table mb-0">
		<thead class="thead-default">
			<tr>
				<th width="10%">Translation zone</th>
				<th width="10%">Shortcode</th>
				<th width="30%">Content</th>
				<?php
					if(count($languages) > 0){
						foreach($languages as $key => $lang){
							?><th class="text-center"><img src="<?=_DOMINIO_.$lang->icon?>" width="20px" /></th><?php
						}
					}
				?>
				<th class="text-right">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if(count($traductions) > 0){
					foreach($traductions as $key => $traduction){
						?>
						<tr>
							<td><?=ucfirst($traduction->traduction_for);?>
							</td>
							<td><?=$traduction->shortcode?></td>
							<td><?=$traduction->content?></td>
							<?php
								if(count($languages) > 0){
									foreach($languages as $key => $lang){

										if(isset($traduction->langs[$lang->slug]) && $traduction->langs[$lang->slug])
											$iconTranslated = '<i class="mdi mdi-check font-size-18 text-success"></i>';
										else
											$iconTranslated = '<i class="mdi mdi-close font-size-18 text-danger"></i>';

										?><td class="text-center"><?=$iconTranslated?></td><?php
										
									}
								}
							?>
							<td class="text-right">

								<!-- ACTIONS ONLY DESKTOP -->
								<div class="only-desktop">
									<a href="javascript:void(0);" onclick="ajaxModalManageTranslation('<?=$traduction->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-translation" class="btn btn-success tooltipTitle" data-placement="top" data-title="Edit translation"><i class="mdi mdi-pencil"></i></a>
									<a href="javascript:void(0)" onclick="ajaxDeleteTranslation('<?=$traduction->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" class="btn btn-danger tooltipTitle" data-placement="top" data-title="Delete translation"><i class="mdi mdi-delete"></i></a>
								</div>

								<!-- ACTIONS ONLY MOBILE -->
								<div class="only-mobile">
									<button id="btnGroupVerticalDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Actions
									</button>
									<div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
										<a href="javascript:void(0);" onclick="ajaxModalManageTranslation('<?=$traduction->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-translation" class="dropdown-item"><i class="mdi mdi-pencil"></i> Edit translation</a>
										<a href="javascript:void(0)" onclick="ajaxDeleteTranslation('<?=$traduction->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" class="dropdown-item"><i class="mdi mdi-delete"></i> Delete translation</a>
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
						<td colspan="<?=count($languages)+4?>" class="text-center"><i class="mdi mdi-alert"></i> No translations found.</td>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>
</div>

<!-- PAGINATOR -->
<div class="row mt-4">
	<div class="col-md-12 text-right">
		<?php $this->app['tools']->getPaginador($pagina, $limite, 'lang', 'getAllTraductionsGroupedFilteredV2', 'ajaxGetTraductionsFiltered'); ?>
	</div>
</div>

<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
	$('.tooltipTitle').tooltip();
</script>