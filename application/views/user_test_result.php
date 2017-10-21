<?php include 'admin_header.php'; ?>
		<!-- Admin Dashboard Starts here -->
			<!-- Header goes here -->
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?=base_url();?>home">Dashboard</a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav" style="float:none;">
							<li><a href="<?=base_url();?>home">Home</a></li>
							<li class="pull-right"><a href="<?=base_url();?>admindashboard/logout">Log Out</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</nav>
			<!-- Header ends here -->
			<!-- Body Content goes here -->
			<section class="adminDashboardView">
				<div>
					<h2><?php echo $app_title; ?></h2>
					<a id="btnExport" class="btn btn-primary pull-right col-md-1 col-sm-1" target="_blank" href="<?=base_url();?>usertestresult/export/<?php echo $application_type;?>" style="width:150px; min-width:inherit; margin-bottom: 2%;">Export</a>
				</div>
				<div class="UserListView">
					<table width="100%" cellspacing="0" cellpadding="0" id="usersTestResultList" class="table table-responsive table-striped">
						<thead>
							<tr>
								<th>Age</th>
								<th>Sex</th>
								<th>File Number</th>
								<th>Type of Data</th>
								<th>Certile</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach ($TestResults as $key => $value) {
						?>
							<tr>
								<td valign="middle"><?=$value['age'];?></td>
								<td><?=$value['gender'];?></td>
								
								<td valign="middle"><?=$value['filenumber'];?></td>
								<td>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Correct Answer</td>
											<?php
												for($intCtr = 0; $intCtr < count($value['test_result']); $intCtr++){
											?>
											<td width="2.3%"><?=$value['test_result'][$intCtr]['answer'];?></td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Responses</td>
											<?php
												for($intCtr = 0; $intCtr< sizeof($value['test_result']); $intCtr++){
											?>
											<td width="2.3%"><?=$value['test_result'][$intCtr]['optionid'];?></td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Points</td>
											<?php
												$intCorrectAnswer = 0;
												for($intCtr = 0; $intCtr< sizeof($value['test_result']); $intCtr++){
											?>
											<td width="2.3%">
												<?php
													if($value['test_result'][$intCtr]['answer'] == $value['test_result'][$intCtr]['optionid'] && $value['test_result'][$intCtr]['includeinscoring'])
													{
														$intCorrectAnswer = $intCorrectAnswer+1;
														echo 1;
													}else
													{
														echo 0;
													}
												?>
											</td>
											<?php } ?>
										</tr>
									</table>
									<?php if($application_type != "tonal")  { ?>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<?php 
												if($application_type == "pitch")
												{
													$value['status'] = $value['pitch_status'];
												}
												else if($application_type == "time") 
												{
													$value['status'] = $value['time_status'];
												}
											?>
											<td width="10%">Practice Responses</td>
											<!-- Check the user application status -->
											<?php 
												if($value['status'] == 1) :
													
											?>
											<td width="2.3%">Next</td>
											<?php
												elseif($value['status'] == 2) :
											?>
												
											<td width="2.3%">More Examples</td>
											<?php
												endif;
											?>
											</td>
											
											<?php
												if($value['status'] == 1) :
													for($intCtr = 0; $intCtr< sizeof($value['practice_result']); $intCtr++) :
											?>
													<td width="2.3%"><?=$value['practice_result'][$intCtr]['optionid'];?></td>
													<?php 
													endfor;
													for($i=0;$i<5;$i++) :
													?>
													<td width="2.3%">0</td>
											<?php
													endfor;
												elseif($value['status'] == 2) :
													for($i=0;$i<2;$i++) :
												?>
													<td width="2.3%">0</td>
												<?php		
													endfor;
													for($intCtr = 0; $intCtr< sizeof($value['practice_result']); $intCtr++) :
												?>
													<td width="2.3%"><?=$value['practice_result'][$intCtr]['optionid'];?></td>
											<?php
													endfor;
												endif;
											?> 
										</tr>
									</table>
									<?php } ?>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td align="right" style="padding:10px;">Item Score : <strong><?=$intCorrectAnswer;?> (<?=sizeof($value['test_result']);?> questions)</strong></td>
										</tr>
									</table>
								</td>
								<td><?=$value['certile'];?></td>
							</tr>	
						<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</section>
			<!-- Body Content ends here -->
		<!-- Admin Dashboard ends here -->
		
		<!-- JS files will load here -->
		<script type="text/javascript" src="<?=base_url();?>resources/js/usersTestResult.js"></script>
		
<?php include 'admin_footer.php'; ?>