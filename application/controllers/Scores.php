<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scores extends CI_Controller {
	
	/**
	 * This is Scores page controller.
	 */
	 
	public function index()
	{
		$user_file_num = $_GET['file_num'];
		
		$this->load->model('adminmodel');
		
		$arrData['user_file_num'] = $user_file_num;
		
		$arrData['User'] = $this->adminmodel->fetch_user($user_file_num);
		
		$intPitchScore = $this->adminmodel->FetchUserResult($arrData['User']['id'], "pitch");
		$arrData['User']['pitch_score'] = $intPitchScore;
		$arrData['User']['pitch_certile'] = $this->adminmodel->FetchCertileWRT($intPitchScore, $arrData['User']['age'], $arrData['User']['gender'], "pitch");
		
		$intTimeScore = $this->adminmodel->FetchUserResult($arrData['User']['id'], "time");
		$arrData['User']['time_score'] = $intTimeScore;
		$arrData['User']['time_certile'] = $this->adminmodel->FetchCertileWRT($intTimeScore, $arrData['User']['age'], $arrData['User']['gender'], "time");
		
		$intTimeScore = $this->adminmodel->FetchUserResult($arrData['User']['id'], "tonal");
		$arrData['User']['tonal_score'] = $intTimeScore;
		$arrData['User']['tonal_certile'] = $this->adminmodel->FetchCertileWRT($intTimeScore, $arrData['User']['age'], $arrData['User']['gender'], "tonal");
				
		$this->load->view('scores', $arrData); 
	   
	}
	
	public function export($file_num)
	{
		$this->load->model('adminmodel');
		
		$arrData['Users'] = $this->adminmodel->fetch_user_test_results($file_num);
		
		foreach ($arrData['Users'] as $key => &$value) 
		{
			$intScore = $this->adminmodel->FetchUserResult($value['id'], $value['app_type']);

			$value['score'] = $intScore;

			$value['certile'] = $this->adminmodel->FetchCertileWRT($intScore, $value['age'], $value['gender'], $value['app_type']);
			
			// Check the Status & then assign the value
			if($value['status'] == 1) 
			{
				$value['status'] = "Next";
			}
			else if($value['status'] == 2)
			{
				$value['status'] = "More Examples";
			} 
			else
			{
				$value['status'] = "";
			}
			
			unset($value['app_type']);
		}
		
		// var_dump($arrData['Users']);
		// die;
		// Enable to download this file
		$filename = "UserList.csv";
		 
		header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: text/csv");
         
        $display = fopen("php://output", 'w');
         
        $arrHeaders = array('ID', 'First Name', 'Last Name', 'Age', 'Gender', 'File Number', 'Created Date', 'Completed Date', 'Active', 'Status','Application Type', 'Score', 'Certile');
        
        fputcsv($display, array_values($arrHeaders), ",", '"');
       
		$users = $arrData['Users'];
		
		if(isset($users))    
		{
            foreach ($users as  $users)
			{
               fputcsv($display, array_values($users), ",", '"');
			}
		}
       
		fclose($display);
	}
	
}
