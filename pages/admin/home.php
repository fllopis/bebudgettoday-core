<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<h4 class="page-title">Dashboard</h4>
			<ol class="breadcrumb">
				<li class="breadcrumb-item active">Welcome to dashboard of fllopis project.</li>
			</ol>
		</div>
	</div>
</div>
<!-- end row -->

<div class="page-content-wrapper">

	<!-- STATS BLOCKS -->
	<div class="row">

		<!-- SKILLS -->
		<div class="col-xl-3 col-md-6">
			<a href="<?=_DOMINIO_._ADMIN_?>skills/" class="card mini-stat position-relative">
				<div class="card-body">
					<div class="mini-stat-desc">
						<h6 class="text-uppercase verti-label text-white-50">Skills</h6>
						<div class="text-white">
							<h6 class="text-uppercase mt-0 text-white-50">Skills</h6>
							<h3 class="mb-3 mt-0"><?=(isset($stats_skills)) ? $stats_skills : '-'?></h3>
						</div>
						<div class="mini-stat-icon">
							<i class="ion ion-code-working display-2"></i>
						</div>
					</div>
				</div>
			</a>
		</div>

		<!-- PROJECTS -->
		<div class="col-xl-3 col-md-6">
			<a href="<?=_DOMINIO_._ADMIN_?>projects/" class="card mini-stat position-relative">
				<div class="card-body">
					<div class="mini-stat-desc">
						<h6 class="text-uppercase verti-label text-white-50">Projects</h6>
						<div class="text-white">
							<h6 class="text-uppercase mt-0 text-white-50">Projects</h6>
							<h3 class="mb-3 mt-0"><?=(isset($stats_projects)) ? $stats_projects : '-'?></h3>
						</div>
						<div class="mini-stat-icon">
							<i class="mdi mdi-folder-open display-2"></i>
						</div>
					</div>
				</div>
			</a>
		</div>

		<!-- EXPERIENCES -->
		<div class="col-xl-3 col-md-6">
			<a href="<?=_DOMINIO_._ADMIN_?>experience/" class="card mini-stat position-relative">
				<div class="card-body">
					<div class="mini-stat-desc">
						<h6 class="text-uppercase verti-label text-white-50">Exper.</h6>
						<div class="text-white">
							<h6 class="text-uppercase mt-0 text-white-50">Experiences</h6>
							<h3 class="mb-3 mt-0"><?=(isset($stats_experiences)) ? $stats_experiences : '-'?></h3>
						</div>
						<div class="mini-stat-icon">
							<i class="mdi mdi-briefcase display-2"></i>
						</div>
					</div>
				</div>
			</a>
		</div>

		<!-- FORMATIONS -->
		<div class="col-xl-3 col-md-6">
			<a href="<?=_DOMINIO_._ADMIN_?>formation/" class="card mini-stat position-relative">
				<div class="card-body">
					<div class="mini-stat-desc">
						<h6 class="text-uppercase verti-label text-white-50">Format.</h6>
						<div class="text-white">
							<h6 class="text-uppercase mt-0 text-white-50">Formations</h6>
							<h3 class="mb-3 mt-0"><?=(isset($stats_formations)) ? $stats_formations : '-'?></h3>
						</div>
						<div class="mini-stat-icon">
							<i class="mdi mdi-book display-2"></i>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
	<!-- end row -->

	<div class="row">

		<!-- LAST PROJECTS CREATED -->
		<div class="col-xl-6 col-md-6 col-xs-12">
			<div class="card">
				<div class="card-body">
					<h4 class="mt-0 font-size-18 mb-5px">Last projects</h4>
					<p class="text-muted mb-15px">Last projects created, click on the edit button to manage the project and change the information.</p>

					<div class="table-responsive">
						<table class="table mb-0">
							<thead class="thead-default">
								<tr>
									<th width="12%" class="text-center">image</th>
									<th>Title</th>
									<th>Category</th>
									<th>Developed in</th>
									<th>Visible</th>
									<th class="text-right">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if(count($lastProjects) > 0){
										foreach($lastProjects as $key => $project){

											?>
											<tr>
												<td class="text-center">
													<img src="<?=_DOMINIO_.$project->image_thumb?>" height="56px" />
												</td>
												<td>
													<?php
														if($project->url != ""){
															?><a href="<?=$project->url?>" target="_blank" class="text-info"><?=$project->title?> <i class="fas fa-external-link-alt font-size-12"></i></a><?php
														} else {
															echo $project->title;
														}
													?>
												</td>
												<td>
													<label class="badge badge-<?=$project->category_badge?> font-size-12"><i class="fa fa-tag font-size-10"></i> <?=$project->category_name?></label>
												</td>
												<td><?=$project->developed_in?></td>
												<td>
													<?php
														if($project->visible == '1'){
															?><label class="badge badge-success font-size-12">Yes</label><?php
														} else {
															?><label class="badge badge-dark font-size-12">No</label><?php
														}
													?>
												</td>
												<td class="text-right">

													<!-- ACTIONS ONLY DESKTOP -->
													<div class="only-desktop">
														<a href="<?=_DOMINIO_._ADMIN_?>project/<?=$project->id?>/" class="btn btn-success tooltipTitle" data-placement="top" data-title="Edit project"><i class="mdi mdi-pencil"></i></a>
													</div>

													<!-- ACTIONS ONLY MOBILE -->
													<div class="only-mobile">
														<button id="btnGroupVerticalDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Actions
														</button>
														<div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
															<a href="<?=_DOMINIO_._ADMIN_?>project/<?=$project->id?>/" class="dropdown-item"><i class="mdi mdi-pencil"></i> Edit project</a>
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
											<td colspan="7" class="text-center"><i class="mdi mdi-alert"></i> No projects found.</td>
										</tr>
										<?php
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- LAST FORMATIONS CREATED -->
		<div class="col-xl-6 col-md-6 col-xs-12">
			<div class="card">
				<div class="card-body">
					<h4 class="mt-0 font-size-18 mb-5px">Last formations</h4>
					<p class="text-muted mb-15px">Last formations created, <a href="<?=_DOMINIO_._ADMIN_?>formation/" class="text-primary">click here</a> to manage the differents formations.</p>

					<div class="table-responsive">
						<table class="table mb-0">
							<thead class="thead-default">
								<th class="text-center" width="12%">Logo</th>
								<th>Enterprise</th>
								<th>Title</th>
								<th>From</th>
								<th>To</th>
							</thead>
							<tbody>
								<?php
									if(count($lastFormations) > 0){
										foreach($lastFormations as $key => $career){

											?>
											<tr>
												<td class="career">
													<img src="<?=_DOMINIO_.$career->logo?>?c=<?=time()?>" height="56px" />
												</td>
												<td><?=$career->enterprise?></td>
												<td><?=$career->title?></td>
												<td>
													<?php
														$careerDateFrom = ($career->date_from_month != "") ? $career->date_from_month . "/" : "";
														$careerDateFrom .= $career->date_from_year;

														if($careerDateFrom != ""){
															?><i class="mdi mdi-calendar"></i> <?=$careerDateFrom;?><?php
														} else {
															echo "-";
														}
													?>
												</td>
												<td>
													<?php
														$careerDateTo = ($career->date_to_month != "") ? $career->date_to_month . "/" : "";
														$careerDateTo .= $career->date_to_year;

														if($careerDateTo != ""){
															?><?=($career->currently) ? "<strong>Currently</strong>" : '<i class="mdi mdi-calendar"></i> ' . $careerDateTo;?><?php
														} else {
															echo "-";
														}
													?>
												</td>
											</tr>
											<?php
										}
									}
									else{
										?>
										<tr>
											<td colspan="5" class="text-center"><i class="mdi mdi-alert"></i> No formations found.</td>
										</tr>
										<?php
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


	</div>

</div>
<!-- end page content-->