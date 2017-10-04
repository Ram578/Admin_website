<?php include 'admin_header.php'; ?>
		<!-- Admin Dashboard Starts here -->
			<!-- Header goes here -->
			
			<!-- Header ends here -->
			<!-- Body Content goes here -->
			<section class="resposesView" style=" padding:5em;" >
				<div>
					<!--<button id="btnExport" class="btn btn-primary pull-right col-md-1 col-sm-1" target="_blank" href="<?=base_url();?>responses/export?file_num=<?php //echo $user_file_num;?>&user_id=<?php //echo $User[0]['id'];?>" style="width:150px; min-width:inherit; margin-bottom: 2%; margin-right:-7em;"> Export </button>-->
					<a id="btnExport" class="btn btn-primary pull-right col-md-1 col-sm-1" target="_blank" href="<?=base_url();?>responses/export?file_num=<?php echo $user_file_num;?>&user_id=<?php echo $User[0]['id'];?>" style="width:150px; min-width:inherit; margin-bottom: 2%; margin-right:-7em;">Export</a>
				</div>
				<div class="UserListView">
					<table width="100%" cellspacing="0" cellpadding="0" id="responses" class="table table-responsive table-striped" style="margin-left:7em;margin-right:2em;">
						<thead>
							<tr>
								<th>Test Name</th>
								<th>Type of Data</th>
								<th>Certile</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Pitch Discrimination</td>
								<td>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Correct Answer</td>
											<?php
												for($intCtr = 0; $intCtr < count($PitchResults['test_result']); $intCtr++){
											?>
											<td width="2.3%"><?=$PitchResults['test_result'][$intCtr]['answer'];?></td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Responses</td>
											<?php
												for($intCtr = 0; $intCtr< sizeof($PitchResults['test_result']); $intCtr++){
											?>
											<td width="2.3%"><?=$PitchResults['test_result'][$intCtr]['optionid'];?></td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Points</td>
											<?php
												$intCorrectAnswer = 0;
												for($intCtr = 0; $intCtr< sizeof($PitchResults['test_result']); $intCtr++){
											?>
											<td width="2.3%">
												<?php
													if($PitchResults['test_result'][$intCtr]['answer'] == $PitchResults['test_result'][$intCtr]['optionid'] && $PitchResults['test_result'][$intCtr]['includeinscoring'])
													{
														$intCorrectAnswer = $intCorrectAnswer+1;
														echo 1;
													}
													else
													{
														echo 0;
													}
												?>
											</td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Practice Responses</td>
											<!-- Check the user status -->
											<?php 
											if($PitchResults['test_result']) :
												if($PitchResults['test_result'][0]['status'] == 1) :
											?>
											<td width="2.3%">Next</td>
											<?php
												elseif($PitchResults['test_result'][0]['status'] == 2) :
											?>
												
											<td width="2.3%">More Examples</td>
											<?php
												endif;
											endif;
											?>
											</td>
											
											<?php
											if($PitchResults['practice_result']) :
												if($PitchResults['practice_result'][0]['status'] == 1) :
													for($intCtr = 0; $intCtr< sizeof($PitchResults['practice_result']); $intCtr++) :
											?>
													<td width="2.3%"><?=$PitchResults['practice_result'][$intCtr]['optionid'];?></td>
													<?php 
													endfor;
													for($i=0;$i<5;$i++) :
													?>
													<td width="2.3%">0</td>
											<?php
													endfor;
												elseif($PitchResults['test_result'][0]['status'] == 2) :
													for($i=0;$i<2;$i++) :
												?>
													<td width="2.3%">0</td>
												<?php		
													endfor;
													for($intCtr = 0; $intCtr< sizeof($PitchResults['practice_result']); $intCtr++) :
												?>
													<td width="2.3%"><?=$PitchResults['practice_result'][$intCtr]['optionid'];?></td>
											<?php
													endfor;
												endif;
											endif;
											?> 
												
										</tr>
									</table>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td align="right" style="padding:10px;">Item Score : <strong><?=$intCorrectAnswer;?> (<?=sizeof($PitchResults['test_result']);?> questions)</strong></td>
										</tr>
									</table>
								</td>
								<td><?=$PitchResults['pitch_certile'];?></td>
							</tr>
									<tbody>
									<tr>
								<td>Time Discrimination</td>
								<td>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Correct Answer</td>
											<?php
												for($intCtr = 0; $intCtr < count($TimeResults['test_result']); $intCtr++){
											?>
											<td width="2.3%"><?=$TimeResults['test_result'][$intCtr]['answer'];?></td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Responses</td>
											<?php
												for($intCtr = 0; $intCtr< sizeof($TimeResults['test_result']); $intCtr++){
											?>
											<td width="2.3%"><?=$TimeResults['test_result'][$intCtr]['optionid'];?></td>
											<?php } ?>
										</tr>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Points</td>
											<?php
												$intCorrectAnswer = 0;
												for($intCtr = 0; $intCtr< sizeof($TimeResults['test_result']); $intCtr++){
											?>
											<td width="2.3%">
												<?php
													if($TimeResults['test_result'][$intCtr]['answer'] == $TimeResults['test_result'][$intCtr]['optionid'] && $TimeResults['test_result'][$intCtr]['includeinscoring'])
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
									<table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
										<tr>
											<td width="10%">Practice Responses</td>
											<!-- Check the user status -->
											<?php 
											
												if($TimeResults['test_result']) :
												if($TimeResults['practice_result'][0]['status'] == 1) :
													
											?>
											<td width="2.3%">Next</td>
											<?php
												elseif($TimeResults['test_result'][0]['status'] == 2) :
											?>
												
											<td width="2.3%">More Examples</td>
											<?php
												endif;
											endif;
											?>
											</td>
											
											<?php
											if($TimeResults['test_result']) :
												if($TimeResults['practice_result'][0]['status'] == 1) :
													for($intCtr = 0; $intCtr< sizeof($TimeResults['practice_result']); $intCtr++) :
											?>
													<td width="2.3%"><?=$TimeResults['practice_result'][$intCtr]['optionid'];?></td>
													<?php 
													endfor;
													for($i=0;$i<5;$i++) :
													?>
													<td width="2.3%">0</td>
											<?php
													endfor;
												elseif($TimeResults['test_result'][0]['status'] == 2) :
													for($i=0;$i<2;$i++) :
												?>
													<td width="2.3%">0</td>
												<?php		
													endfor;
													for($intCtr = 0; $intCtr< sizeof($TimeResults['practice_result']); $intCtr++) :
												?>
													<td width="2.3%"><?=$TimeResults['practice_result'][$intCtr]['optionid'];?></td>
											<?php
													endfor;
												endif;
											endif;
											?> 
										</tr>
									</table>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td align="right" style="padding:10px;">Item Score : <strong><?=$intCorrectAnswer;?> (<?=sizeof($TimeResults['test_result']);?> questions)</strong></td>
										</tr>
									</table>
								</td>
								<td><?=$TimeResults['time_certile'];?>
								</td>
							</tr>	
									</tbody>
						</tbody>
					</table>
				</div>
			</section>
			<!-- Body Content ends here -->
		<!-- Admin Dashboard ends here -->
		
		<!-- JS files will load here -->
		<script type="text/javascript" src="<?=base_url();?>resources/js/responses.js"></script>
		
<?php include 'admin_footer.php'; ?>