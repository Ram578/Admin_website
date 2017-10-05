<html>
<head>
<link rel="stylesheet" href="resources/css/website.css">
</head>
 <script type="text/javascript">
		var strBaseURL = "<?=base_url();?>";
</script> 

	<div class="login-html">
		<div class="login testing">
		
			<div class="testing" style="height:100%;background-color:#9e9e9e;">
			<!-- Testresult Block goes here -->
				<form class="form-horizontal col-lg-4 col-md-5 col-sm-6 col-xs-6" role="form" id="testapplication" action="testing/test_results" method="POST">
					<!--h2>testresult Form</h2-->
					<div class="testresult">
						<label for="sleAge" id="selectedLabel" style="padding:5px;">Test Name : </label>
						<select class="form-control"  id="select" name="application" style="padding: 4px;">
								  <option value="pitch">Pitch Discrimination</option>
								  <option value="time">Time Disrimination</option>
								  <option value="tonal">Tonal Memory</option>
						</select>
						<div class="col_full form-group" style="padding:2em;">
							<div class="row" style="margin-top: 20%;">
								 <div class="col-sm-4">
									<label class="radio-inline" id="selectedLabel">
										<input type="radio" id="userslist" value="userslist"  name="table_type" checked="checked" > UsersList
									</label>
								</div>
								<div class="col-sm-4" id="radioselect">
									<label class="radio-inline"  id="selectedLabel">
										<input type="radio" id="testresult" name="table_type"   value="test_result"  > TestResult
									</label>
								</div>
							</div>
						</div>
					   <!-- /.form-group -->	
						<div class="form-group" >
							<button type="submit" id="testname" class="btn btn-primary btn-block">Submit</button>
						</div>
					</div>
				</form>
				<form class="form-horizontal col-lg-4 col-md-5 col-sm-6 col-xs-6" role="form" id="fileapplication" action="testing/user_total_results" method="POST">
					<div class="filenumber" style="width:75%; margin-left:8%;">
						<div class="col_full form-group ">
							<label for="sleAge"  id="selectedLabel" style="padding:5px;">File Number : </label>
							<input type="text" id="filenumber" class="form-control" name="filenumber"  autocomplete="false" style="padding:4px;"/>
						</div>
						<div class="col_full form-group" style="padding:2em; margin-left:18px;">
							<div class="row">
								 <div class="col-sm-4">
									<label class="radio-inline"  id="selectedLabel">
										<input type="radio" id="scores" name="test_type" value="scores" checked ="checked"> Scores
									</label>	
								</div>
								<div class="col-sm-4">
									<label class="radio-inline"  id="selectedLabel">	
										<input type="radio" id="radioselect" name="test_type" value="responses"> Responses
									</label>
								</div>
							</div>
						</div>
					   <!-- /.form-group -->	
						<div class="form-group">
							<button type="submit" id="TesingButton" class="btn btn-primary btn-block" style="background-color: #3F51B5; width: 18em;border-radius: 4px; height: 2em;">Submit</button>
						</div>
					</div>
				</form>
			<!-- </form> <!-- /form --> 
				<!-- testresult Block ends here -->
			</div>
			
		</div>
	</div>

		
