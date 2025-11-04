<?php
	$totalCols = 5;
?>
<div class="table-responsive">
	<table class="table mb-0">
		<thead class="thead-default">
			<tr>
                <th>Title</th>
				<th>H1</th>
                <th class="text-center">SEO Title</th>
                <th class="text-center">SEO Description</th>
				<th class="text-center">Visible</th>
				<?php
					if(count($languages) > 0){
						foreach($languages as $key => $lang){
							$totalCols++;
							?><th class="text-center"><img src="<?=_DOMINIO_.$lang->icon?>" width="20px" /></th><?php
						}
					}
				?>
				<th class="text-right">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if(count($pages) > 0){
					foreach($pages as $key => $page){

						?>
						<tr>
                            <td>
								<?php
									if(isset($page->slug) && $page->slug != ""){
										?>
											<a href="<?=_DOMINIO_.$page->slug?>/" targe="_blank" class="text-info">
												<i class="mdi mdi-file font-size-17"></i> <?=$page->title?>
											</a>
										<?php
									} else {
										?><i class="mdi mdi-file font-size-17"></i> <?=$page->title?><?php
									}
								?>	
							</td>
                            <td><?=$page->h1?></td>
                            <td class="text-center">
								<?php
									if(isset($page->seo_title) && $page->seo_title != "")
										echo '<i class="mdi mdi-check font-size-18 text-success"></i>';
									else
										echo '<i class="mdi mdi-close font-size-18 text-danger"></i>';
								?>
                            </td>
                            <td class="text-center">
								<?php
									if(isset($page->seo_description) && $page->seo_description != "")
										echo '<i class="mdi mdi-check font-size-18 text-success"></i>';
									else
										echo '<i class="mdi mdi-close font-size-18 text-danger"></i>';
								?>
                            </td>
							<td class="text-center">
								<?php
									if($page->visible == '1'){
										?><label class="badge badge-success font-size-12">Yes</label><?php
									} else {
										?><label class="badge badge-dark font-size-12">No</label><?php
									}
								?>
							</td>
							<?php
								if(count($languages) > 0){
									foreach($languages as $key => $lang){
										if(isset($page->langs[$lang->slug]) && $page->langs[$lang->slug])
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
									<a href="javascript:void(0);" onclick="ajaxModalManagePage('<?=$page->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-page" class="btn btn-success tooltipTitle" data-placement="top" data-title="Edit page"><i class="mdi mdi-pencil"></i></a>
									<a href="javascript:void(0)" onclick="ajaxDeletePage('<?=$page->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" class="btn btn-danger tooltipTitle" data-placement="top" data-title="Delete page"><i class="mdi mdi-delete"></i></a>
								</div>

								<!-- ACTIONS ONLY MOBILE -->
								<div class="only-mobile">
									<button id="btnGroupVerticalDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Actions
									</button>
									<div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
										<a href="javascript:void(0);" onclick="ajaxModalManagePage('<?=$page->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" data-toggle="modal" data-target=".manage-page" class="dropdown-item"><i class="mdi mdi-pencil"></i> Edit page</a>
                                        <a href="javascript:void(0)" onclick="ajaxDeletePage('<?=$page->id?>', '<?=$comienzo?>', '<?=$limite?>', '<?=$pagina?>');" class="dropdown-item"><i class="mdi mdi-delete"></i> Delete page</a>
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
						<td colspan="<?=$totalCols?>" class="text-center"><i class="mdi mdi-alert"></i> No pages found.</td>
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
		<?php $this->app['tools']->getPaginador($pagina, $limite, 'Pages', 'getPagesFiltered', 'ajaxGetPagesFiltered'); ?>
	</div>
</div>

<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
	$('.tooltipTitle').tooltip();
</script>