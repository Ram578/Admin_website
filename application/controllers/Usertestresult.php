<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usertestresult extends CI_Controller {

	/**
	 * This is NewExampleInfo page controller.
	 * Develope on 19th July'2016 by Hemanth Kumar
	 */
	public function index()
	{
		$this->load->model('adminmodel');
		
		$application_type = $_GET['type'];
			
		if($application_type == "pitch") 
		{
			$arrData['app_title'] = "Pitch Discrimination";
		}
		else if($application_type == "time") 
		{
			$arrData['app_title'] = "Time Discrimination";
		}
		else if($application_type == "tonal") 
		{
			$arrData['app_title'] = "Tonal Discrimination";
		}
		
		$arrData['application_type'] = $application_type;
		
		$arrData['TestResults'] = $this->adminmodel->FetchTestResult($application_type);
						
		foreach ($arrData['TestResults'] as $key => &$value) 
		{
			$intScore = $this->adminmodel->FetchUserResult($value['id'], $application_type);

			$value['score'] = $intScore;

			$value['certile'] = $this->adminmodel->FetchCertileWRT($intScore, $value['age'], $value['gender'], $application_type);
		}

		$this->load->view('user_test_result', $arrData);
	}

	public function export($type)
	{
		$this->load->model('adminmodel');

		$arrResult = $this->adminmodel->FetchTestResult($type);

		$arrTemp = array();

		$arrHeaders = array('ID', 'First Name', 'Last Name', 'Age', 'Gender', 'File Number', 'Score', 'Certile', 'P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7');
		
		foreach ($arrResult as $key => &$value) 
		{
			$intScore = $this->adminmodel->FetchUserResult($value['id'], $type);

			$value['score'] = $intScore;

			$value['certile'] = $this->adminmodel->FetchCertileWRT($intScore, $value['age'], $value['gender'], $type);
		}
		
		foreach ($arrResult as $key => &$value) 
		{
			//Check the application type for completed date and status
			if($type == "pitch") 
			{
				$value['status'] =  $value['pitch_status'];
			}
			else if($type == "time") 
			{
				$value['status'] =  $value['time_status'];
			}
			else if($type == "tonal")
			{
				$value['status'] =  $value['tonal_status'];
			}
			
			if(count($value['practice_result']) > 0)
			{
				if($value['status'] == 1) {
					$practiceintQt = 1;
					foreach ($value['practice_result'] as $key => $qt) 
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
				elseif($value['status'] == 2)
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
			if(count($value['test_result']) > 0)
			{
				foreach ($value['test_result'] as $key => $qt) {
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
			unset($arrTempRow['status']);
			unset($arrTempRow['pitch_status']);
			unset($arrTempRow['time_status']);
			unset($arrTempRow['tonal_status']);
			unset($arrTempRow['pitch_completed_date']);
			unset($arrTempRow['time_completed_date']);
			unset($arrTempRow['tonal_completed_date']);
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
		// foreach ($arrTemp as $key => &$value) 
		// {
			// $intScore = $this->adminmodel->FetchUserResult($value['id'], $type);

			// $value['score'] = $intScore;

			// $value['certile'] = $this->adminmodel->FetchCertileWRT($intScore, $value['age'], $value['gender'], $type);
		// }

		// $arrHeaders[] = 'Score';
		// $arrHeaders[] = 'Certile';

		$arrHeaders = array_unique($arrHeaders);
		
		// Enable to download this file
		$filename = ucfirst($type)."UsersTestResult.csv";
		 		
		header("Pragma: public");
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		ob_clean();

		$display = fopen("php://output", 'w');

		fputcsv($display, array_values($arrHeaders), ",", '"');
		
		foreach ($arrTemp as $file) 
		{
		    $result = [];
		    array_walk_recursive($file, function($item) use (&$result) {
		        $result[] = $item;
		    });
		    fputcsv($display, $result);
		}

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
