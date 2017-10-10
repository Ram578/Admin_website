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
			<section class="adminDashboardView">
				<div class="UserListView container">
					<h2><?php echo $app_title; ?></h2>
					<div>
						<a id="btnExport" class="btn btn-primary pull-right col-md-1 col-sm-1" target="_blank" href="<?=base_url();?>userslist/export/<?php echo $application_type;?>" style="width:150px; min-width:inherit; margin-bottom: 2%;">Export</a>
					</div>
					<table width="100%" id="tblCustomerList" cellspacing="0" cellpadding="0" class="table table-responsive table-striped">
						<thead>
							<tr>
								<th>Age</th>
								<th>Gender</th>
								<th>File Number</th>
								<th>Joined Date</th>
								<th>Completed Date</th>
								<th>Score</th>
								<th>Certile</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($Users as $user){
							?>
								<tr>
									<td><?=$user['age'];?></td>
									<td><?=$user['gender'];?></td>
									<td><?=$user['filenumber'];?></td>
									<td><?=$user['addeddate'];?></td>
									<?php
										if($application_type == "pitch") 
										{
											$completed_date = $user['pitch_completed_date'];
										} 
										elseif($application_type == "time") 
										{
											$completed_date = $user['time_completed_date'];
										} 
										else 
										{
											$completed_date = $user['tonal_completed_date'];
										}
									?>
									<td><?php echo $completed_date;?></td>
									<td><?=$user['score'];?></td>
									<td><?=$user['certile'];?></td>
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
		
		<!-- JS file  -->
		<script type="text/javascript" src="<?=base_url();?>resources/js/userslist.js"></script>
<?php include 'admin_footer.php'; ?>