<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scores extends CI_Controller {

	public function index()
	{
		$user_file_num = $_GET['file_num'];
		
		$this->load->model('adminmodel');
		
		$arrData['user_file_num'] = $user_file_num;
		
		$arrData['User'] = $this->adminmodel->fetch_user($user_file_num);
		
		$arrData['PitchResults'] = $this->adminmodel->FetchUserTestResult("pitch", $arrData['User'][0]['id']);
		// var_dump($arrData['PitchResults']);
		// die;
		
		$arrData['TimeResults'] = $this->adminmodel->FetchUserTestResult("time",$arrData['User'][0]['id']);
	   // $arrData['TonalResults'] = $this->adminmodel->FetchUserTestResult("tonal",$arrData['User'][0]['id']);
		
		// var_dump($arrData);
				
					$intPitchScore = $this->adminmodel->FetchPitchUserResult($arrData['User'][0]['id']);
					
					$intTimeScore = $this->adminmodel->FetchTimeUserResult($arrData['User'][0]['id']);
					
					$arrData['User'][0]['pitch_score'] = $intPitchScore;

					$arrData['User'][0]['pitch_certile'] = $this->adminmodel->FetchPitchCertileWRT($intPitchScore, $arrData['User'][0]['age'], $arrData['User'][0]['gender']);
					$arrData['User'][0]['time_score'] = $intTimeScore;

					$arrData['User'][0]['time_certile'] = $this->adminmodel->FetchTimeCertileWRT($intTimeScore, $arrData['User'][0]['age'], $arrData['User'][0]['gender']);
					// var_dump($arrData);
				
				
	   $this->load->view('scores', $arrData); 
	   
	}
	
	
	//export user data in csv file
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
