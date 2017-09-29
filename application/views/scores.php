<?php include 'admin_header.php'; ?>
		<!-- Admin Dashboard Starts here -->
			<!-- Header goes here -->
			
			<!-- Header ends here -->
			<!-- Body Content goes here -->
			<section class="adminDashboardView" style="width:90%; margin-left:40px; margin-top:0px;">
				<div>
						
					<button id="btnExport" class="btn btn-primary pull-right col-md-1 col-sm-1" target="_blank" href='<?=base_url();?>scores/export?file_num=<?php echo $user_file_num;?>&user_id=<?php echo $User[0]['id'];?>' style="width:150px; min-width:inherit; margin-bottom: 2%; margin-left:20px; margin-right:3em;"> Export </button>
					<button id="btnPrint" class="btn btn-primary pull-right col-md-1 col-sm-1" target="_blank" href='<?=base_url();?>' style="width:150px; min-width:inherit; margin-bottom: 2%; margin-left:29px; margin-right:2em;"> Print </button>
				</div>
				<div class="background" style="disaplay: none;">
				</div>
				<div><p><h3 style="text-align:center;"><b>AIMS Testing Results</b><h3></p></div>
				<div class="header" style="padding:2em; margin-left:12px;">
					<div><b>First Name: </b><?php echo $User[0]['firstname']; ?></div>
					<div><b>Last Name: </b><?php echo $User[0]['lastname']; ?></div>
					<div><b>Filenumber:</b> <?php echo $User[0]['filenumber']; ?></div>
					<div><b>Age:</b> <?php echo $User[0]['age']; ?></div>
				</div>
				<div class="UserListView" style="padding:3em; ">
					<table width="100%" cellspacing="0" cellpadding="0" id="scores" class="table table-responsive table-striped">
						<thead>
							<tr>
								<th>Test Name</th>
								<th>Completed Date</th>
								<th>Score</th>
								<th>Certile</th>
							</tr>
						</thead>
						<tbody>
									
									<td>Pitch Discrimination</td>
									<td><?php echo $User[0]['pitch_completed_date']; ?></td>
									<td><?php echo $User[0]['pitch_score']; ?></td>
									<td><?php echo $User[0]['pitch_certile'];       ?></td>
						
							
						</tbody>
									<td>Time Discrimination</td>
									<td><?php echo $User[0]['time_completed_date']; ?></td>
									<td><?php echo $User[0]['time_score'];?></td>
									<td><?php echo $User[0]['time_certile'];?></td>
						<tbody>
						<tbody>
									<td>Tonal Discrimination</td>
									<td><?php echo $User[0]['tonal_completed_date']; ?></td>
									<!--<td><?php //echo $User[0]['tonal_score'];?></td>
									<!--<td><?php //echo $User[0]['tonal_certile'];?></td>-->
						</tbody>
						</tbody>
					</table>
				</div>
			<!--</div>-->
			</section>
			<!-- Body Content ends here -->
		<!-- Admin Dashboard ends here -->
		
		<!-- JS files will load here -->
		<script type="text/javascript" src="<?=base_url();?>resources/js/scores.js"></script>
		
<?php include 'admin_footer.php'; ?>