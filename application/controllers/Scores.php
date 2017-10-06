<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scores extends CI_Controller {

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
				
		$this->load->view('scores', $arrData); 
	   
	}
	
	
	//export user data in csv file
	public function export()
	{
		$this->load->model('adminmodel');
		
		$user_file_num = $_GET['file_num'];
		$user_id = $_GET['user_id'];
		
		$arrData['User']= $this->adminmodel->fetch_user($user_file_num);
		
		foreach ($arrData['User'] as $key => &$value) 
		{
			
			$intScore = $this->adminmodel->FetchPitchUserResult($user_id);
			
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
				$value[0]['status'] = "";
			}

			$value['score'] = $intScore;

			$value['certile'] = $this->adminmodel->FetchPitchCertileWRT($intScore, $value['age'], $value['gender']);
		}
		
		// Enable to download this file
		$filename = "Scores.csv";
		 
		header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: text/csv");
         
        $display = fopen("php://output", 'w');
         
        $arrHeaders = array( 'ID','First Name','Last Name','Age', 'Gender', 'File Number', 'Created Date', 'Completed Date', 'Score', 'Certile');
        
        fputcsv($display, array_values($arrHeaders), ",", '"');
       
		$users = $arrData['User'];
		
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
