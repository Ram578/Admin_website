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
	public function export()
	{
		$user_file_num = $_GET['file_num'];
		$user_id = $_GET['user_id'];
		
		
		// var_dump($user_file_num);
		// var_dump($user_id);
	
		$this->load->model('adminmodel');
		
		$arrResult = $this->adminmodel->fetch_user($user_file_num);
		
		
		 // var_dump($arrResult);
		 // die;
		 
		$arrTemp = array();
		$arrHeaders = array('id', 'firstname', 'lastname', 'filenumber','age','pitch_completed_date','time_completed_date','tonal_completed_date');
			
			foreach ($arrHeaders as $key) {
				$arrTemp[$key] = $arrResult[0][$key];
				 var_dump($arrTemp[$key]);
			}
			die;
			// die;
				// var_dump($arrTemp);
			 // die;
			 // $arrResult[0]['pitch_certile'];
			 // var_dump( $arrResult[0]['pitch_certile']);
			 
			
			/*if(count($arrResult['practice_result']) > 0)
			{
				if($arrResult[0]['status'] == 1) {
					$practiceintQt = 1;
					foreach ($arrResult['practice_result'] as $key => $qt) 
					{
						$value['Practice '.$practiceintQt] = $qt['optionid'];
						$practiceintQt++;
					}
					
					$value['Practice 3'] = '0';
					$value['Practice 4'] = '0';
					$value['Practice 5'] = '0';
					$value['Practice 6'] = '0';
					$value['Practice 7'] = '0';
					
				} 
				elseif($arrResult[0]['status'] == 2)
				{
					$value['Practice 1'] = '0';
					$value['Practice 2'] = '0';
					$practiceintQt = 3;
					foreach ($value['practice_result'] as $key => $qt) 
					{
						$value['Practice '.$practiceintQt] = $qt['optionid'];
						$practiceintQt++;
					}
				}
				
			}
			
			$intQt = 1;
			if(count($arrResult['test_result']) > 0)
			{
				foreach ($arrResult['test_result'] as $key => $qt) {
					$value['Answer '.$intQt] = $qt['optionid'];
					$arrHeaders[] = $intQt;
					$intQt++;
				}
			}

			$arrTempRow = $value;
			unset($arrTempRow['test_result']);
			unset($arrTempRow['practice_result']);
			unset($arrTempRow['active']);
			unset($arrTempRow['addeddate']);
			unset($arrTempRow['completeddate']);
			unset($arrTempRow['status']);
			$arrTemp[] = $arrTempRow;
		 }
		
		$maxColumns = max(array_map(function($row){
			    return count($row);
			}, $arrTemp));

		//$this->cleanArray($arrTemp);
		
		//If there is no values then it gives empty values
		foreach ($arrTemp as &$value) 
		{
			$intTempCount = count($value);
			if($maxColumns > $intTempCount)
			{
				for($intCtr = ($intTempCount-13); $intCtr < ($maxColumns-$intTempCount); $intCtr++)
				{
					$value['Answer '.($intCtr+1)] = ' ';
				}
			}
		}
		//print_r($arrTemp); exit;
		foreach ($arrTemp as $key => &$value) 
		{
			 $intScore = $this->adminmodel->FetchPitchUserResult( $user_id);

			 $value['score'] = $intScore;

			 $value['certile'] = $this->adminmodel->FetchCertileWRT($intScore, $value['age'], $value['gender']);
		}

		 $arrHeaders[] = 'Score';
		 $arrHeaders[] = 'Certile';

		 $arrHeaders = array_unique($arrHeaders);*/
		
		// Enable to download this file
		$filename = "UserScores.csv";
		 		
		header("Pragma: public");
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		ob_clean();

		$display = fopen("php://output", 'w');

		fputcsv($display, array_values($arrHeaders), ",", '"');
		
		// foreach ($arrTemp as $file) 
		// {
		    // $result = [];
		    // array_walk_recursive($file, function($item) use (&$result) {
		        // $result[] = $item;
		    // });
		    // fputcsv($display, $result);
		// }

		$flag = false;
		/*if(count($arrTemp)) {
		    if(!$flag) {
		      // display field/column names as first row
		      fputcsv($display, array_values($arrHeaders), ",", '"');
		      $flag = true;
		    }
		    foreach ($arrTemp as $key => $value) {
			    fputcsv($display, array_values($value), ",", '"');
			}
		  } 
		  */
		 
		fclose($display);
		
	}

	function cleanArray(&$array)
	{
	    end($array);
	    $max = key($array); //Get the final key as max!
	    for($i = 0; $i < $max; $i++)
	    {
	        if(!isset($array[$i]))
	        {
	            $array[$i] = '';
	        }
	    }
	}
}