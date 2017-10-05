<?php include 'admin_header.php'; ?>
	<script type="text/javascript">
		var strBaseURL = "<?=base_url();?>";
	</script>
		<!-- Admin Dashboard Starts here -->
			<!-- Header goes here -->
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?=base_url();?>admindashboard">Dashboard</a>
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
				<div class="UserListView container">
					<div class="btns">
						<a id="btnPrint" class="btn btn-primary col-md-1 col-sm-1" target="_blank" style="width:150px; min-width:inherit; margin-bottom:2%;">Print</a>
						<a id="btnExport" class="btn btn-primary col-md-1 col-sm-1" target="_blank" href="<?=base_url();?>scores/export/<?php echo $user_file_num;?>" style="width:150px; min-width:inherit; margin-bottom:2%; left:1%;">Export</a>
					</div>
					<div class="background"></div>
					<div style="margin:5%;">
						<h2 align="center">AIMS Testing Results</h2>
					</div>
					<div style="margin-bottom:3%;">
						<div><b>First Name: </b><?php echo $User[0]['firstname']; ?></div>
						<div><b>Last Name: </b><?php echo $User[0]['lastname']; ?></div>
						<div><b>File Number:</b> <?php echo $User[0]['filenumber']; ?></div>
						<div><b>Age:</b> <?php echo $User[0]['age']; ?></div>
					</div>
					<table cellspacing="0" cellpadding="0" class="table table-responsive table-striped">
						<thead>
							<tr>
								<th>Test Name</th>
								<th>Completed Date</th>
								<th>Score</th>
								<th>Certile</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Pitch Discrimination</td>
								<td><?php echo $User[0]['pitch_completed_date']; ?></td>
								<td><?php echo $User[0]['pitch_score']; ?></td>
								<td><?php echo $User[0]['pitch_certile']; ?></td>
							</tr>
							<tr>
								<td>Time Discrimination</td>
								<td><?php echo $User[0]['time_completed_date']; ?></td>
								<td><?php echo $User[0]['time_score'];?></td>
								<td><?php echo $User[0]['time_certile'];?></td>
							</tr>
							<tr>
								<td>Tonal Discrimination</td>
								<td><?php echo $User[0]['tonal_completed_date']; ?></td>
								<td><?php //echo $User[0]['tonal_score'];?></td>
								<td><?php //echo $User[0]['tonal_certile'];?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
			<!-- Body Content ends here -->
		<!-- Admin Dashboard ends here -->
		
		<!-- JS file  -->
		<script type="text/javascript" src="<?=base_url();?>resources/js/scores.js"></script>
<?php include 'admin_footer.php'; ?>
