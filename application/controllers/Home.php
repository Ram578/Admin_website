<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * This is Home page controller.
	 */
	public function index()
	{
		if(!isset($this->session->userdata['EmployeeID']))
		{
			redirect('admin', 'refresh');
		}
		else
    	{
			$this->load->view('home'); 
    	}
	}
	
	public function test_results() 
	{
		$application_type = $_POST['application'];
		
		$table_type = $_POST['table_type'];
	 
		if($table_type == "userslist") 
		{
			if($application_type == "pitch") 
			{
				redirect('userslist?type='.$application_type, 'refresh');
			}
			else if($application_type == "time") 
			{
				redirect('userslist?type='.$application_type, 'refresh');
			}
			else if($application_type == "tonal")
			{
				redirect('userslist?type='.$application_type, 'refresh');
			}	
		}
		else if($table_type == "test_result") 
		{
			if($application_type == "pitch")
			{
				redirect('usertestresult?type='.$application_type, 'refresh');
			}
			else if($application_type == "time") 
			{
				redirect('usertestresult?type='.$application_type, 'refresh');
			}
			else if($application_type == "tonal")
			{
				redirect('usertestresult?type='.$application_type, 'refresh');
			}	
		}
	}
	
	function user_total_results() 
	{
		$filenumber = trim($_POST['filenumber']);
		$test_type = $_POST['test_type'];
		
		if($filenumber != "")
		{
			if($test_type == 'scores') 
			{
				redirect('scores?file_num='.$filenumber, 'refresh');
			}
			else
			{
				redirect('responses?file_num='.$filenumber, 'refresh');
			}
		} 
		else {
			redirect('home', 'refresh');
		}
	} 
	
}
	
	

