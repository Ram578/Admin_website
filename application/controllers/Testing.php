<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	/**
	 * This is TonalTest page controller.
	 * Develope on 21th July'2016 by Hemanth Kumar
	 */
	public function index()
	{
		if(!isset($this->session->userdata['EmployeeID']))
		{
			redirect('login', 'refresh');
		}else
    	{
			$this->load->view('testing'); 
    	}
	}
	
	
	public function test_results() {
		
		
	
		$application_type = $_POST['application'];
		
		// var_dump($application_type);
		
		$table_type = $_POST['table_type'];
	 
		if($table_type == "test_result") {
			
			if($application_type == "pitch")
			{
				redirect('Usertestresult?type='.$application_type, 'refresh');
			}
			else if($application_type == "time") 
			{
				redirect('Usertestresult?type='.$application_type, 'refresh');
			}
			else if($application_type == "tonal")
			{
				redirect('Usertestresult?type='.$application_type, 'refresh');
			}	
			
		} else if($table_type == "userslist") {
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
    
		
	}
	function user_total_results() {
		
		$filenumber = $_POST['filenumber'];
		$test_type = $_POST['test_type'];
		// var_dump($filenumber);
		
		if($filenumber != ""){
			if($test_type == 'scores') 
			{
				redirect('scores?file_num='.$filenumber, 'refresh');
			}
			else
			{
				redirect('responses?file_num='.$filenumber, 'refresh');
			}
		} 
	
	} 
			
		
	
		
}
	
	

