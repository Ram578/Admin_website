<?php include 'admin_header.php'; ?>
	<script type="text/javascript">
		var strBaseURL = "<?=base_url();?>";
	</script>
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
			<section class="adminDashboardView" id="printDashboardView">
				<div class="ScoresListView container">
					<div class="btns-scores">
						<a id="btnPrint" class="btn btn-primary">Print</a>
						<a id="btnExport" class="btn btn-primary" target="_blank" href="<?=base_url();?>scores/export/<?php echo $user_file_num;?>">Export</a>
					</div>
					<div class="scores-header">
						<div class="scores-logo">
							<div class="imgLogo"></div>
							<h2>AIMS Testing Results</h2>
						</div>
					</div>
					<div style="margin-bottom:3%;">
						<div><b>First Name: </b><?php echo $User['firstname']; ?></div>
						<div><b>Last Name: </b><?php echo $User['lastname']; ?></div>
						<div><b>File Number:</b> <?php echo $User['filenumber']; ?></div>
						<div><b>Age:</b> <?php echo $User['age']; ?></div>
					</div>
					<table cellspacing="0" cellpadding="10" class="score-table" id="scorestable" style="padding:5em;">
						<thead>
							<tr class="table-headings">
								<th>Test Name</th>
								<th>Completed Date</th>
								<th>Score</th>
								<th>Certile</th>
							</tr>
						</thead>
						<tbody>
							<?php if($User['pitch_completed_date'] != "0000-00-00 00:00:00") { ?>
							<tr>
								<td>Pitch Discrimination</td>
								<td><?php echo $User['pitch_completed_date']; ?></td>
								<td><?php echo $User['pitch_score']; ?></td>
								<td><?php echo $User['pitch_certile']; ?></td>
							</tr>
							<?php 
								}
								if($User['time_completed_date'] != "0000-00-00 00:00:00") {
							?>
							<tr>
								<td>Time Discrimination</td>
								<td><?php echo $User['time_completed_date']; ?></td>
								<td><?php echo $User['time_score'];?></td>
								<td><?php echo $User['time_certile'];?></td>
							</tr>
								<?php } ?>
						</tbody>
					</table>
				</div>
			</section>
		<!-- Body Content ends here -->
		<!-- Admin Dashboard ends here -->
		<!-- JS file  -->
<?php include 'admin_footer.php'; ?>

		<script type="text/javascript" src="<?=base_url();?>resources/js/scores.js"></script>
