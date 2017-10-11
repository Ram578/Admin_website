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
							<li class=""><a href="<?=base_url();?>home">Home</a></li>
							<!--<li><a href="<?//=base_url();?>userslist">Users List</a></li>
							<li><a href="<?//=base_url();?>usertestresult">Test Result</a></li> -->
							<li class="pull-right"><a href="<?=base_url();?>admindashboard/logout">Log Out</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</nav>
			<!-- Header ends here -->
			<!-- Body Content goes here -->
			<section class="homeDashboardView">
				<div class="homeView container">
					<!-- Testresult Block goes here -->
					<div class="row">
						<div class="col-md-6">
							<form role="form" id="testapplication" action="home/test_results" method="POST">
								<div class="testresult">
									<div class="col_full form-group">
										<label for="sleAge" id="selectedLabel">Test Name : </label>
										<select class="form-control"  id="select" name="application">
											<option value="pitch">Pitch Discrimination</option>
											<option value="time">Time Disrimination</option>
											<!-- <option value="tonal">Tonal Memory</option> -->
										</select>
									</div>
									<div class="col_full form-group" style="padding:1em;">
										<div class="row">
											 <div class="col-sm-4">
												<label class="radio-inline" id="selectedLabel">
													<input type="radio" id="userslist" value="userslist" name="table_type" checked="checked">Users List
												</label>
											</div>
											<div class="col-sm-4" id="radioselect">
												<label class="radio-inline"  id="selectedLabel">
													<input type="radio" id="testresult" name="table_type" value="test_result">Test Result
												</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" id="TesingButton" class="btn btn-primary btn-block">Submit</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-6">
							<form role="form" id="fileapplication" action="home/user_total_results" method="POST">
								<div class="filenumber">
									<div class="col_full form-group">
										<label for="sleAge" id="selectedLabel">File Number : </label>
										<input type="text" id="filenumber" class="form-control" name="filenumber" autocomplete="false" required />
									</div>
									<div class="col_full form-group" style="padding:1em;">
										<div class="row">
											 <div class="col-sm-4">
												<label class="radio-inline" id="selectedLabel">
													<input type="radio" id="scores" name="test_type" value="scores" checked="checked">Scores
												</label>	
											</div>
											<div class="col-sm-4">
												<label class="radio-inline" id="selectedLabel">	
													<input type="radio" id="radioselect" name="test_type" value="responses">Responses
												</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" id="TesingButton" class="btn btn-primary btn-block">Submit</button>
									</div>
								</div>
							</form>
						</div>
					<!-- testresult Block ends here -->
					</div>
				</div>
			</section>
			
<footer class="footer">
</footer>
			<!-- Body Content ends here -->
		<!-- Admin Dashboard ends here -->
<?php include 'admin_footer.php'; ?>