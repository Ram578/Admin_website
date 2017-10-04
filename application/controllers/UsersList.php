<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Responses extends CI_Controller {
    /**
     * This is TonalTest page controller.
     * Develope on 21th July'2016 by Hemanth Kumar
     */
    public function index()
    {
        $user_file_num = $_GET['file_num'];
        
        // $user_id = $_GET['user_id'];
                
        $this->load->model('adminmodel');
        
        $arrData['user_file_num'] = $user_file_num;
        
        // $arrData['user_id'] = $user_id;
        
        $arrData['User'] = $this->adminmodel->fetch_user($user_file_num);
        $arrData['PitchResults'] = $this->adminmodel->FetchUserTestResult("pitch", $arrData['User'][0]['id']);
        // var_dump($arrData['PitchResults'] );
        $arrData['TimeResults'] = $this->adminmodel->FetchUserTestResult("time",$arrData['User'][0]['id']);
        // $arrData['TonalResults'] = $this->adminmodel->FetchUserTestResult("tonal",$arrData['User'][0]['id']);
         
          // var_dump($arrData);
            
         $intPitchScore = $this->adminmodel->FetchPitchUserResult($arrData['User'][0]['id']);
         $intTimeScore = $this->adminmodel->FetchTimeUserResult($arrData['User'][0]['id']);
        
        
         $arrData['PitchResults']['pitch_score'] = $intPitchScore;
         $arrData['PitchResults']['pitch_certile'] = $this->adminmodel->FetchPitchCertileWRT($intPitchScore, $arrData['User'][0]['age'], $arrData['User'][0]['gender']);
                
         $arrData['TimeResults']['time_score'] = $intTimeScore;
         $arrData['TimeResults']['time_certile'] = $this->adminmodel->FetchTitchCertileWRT($intTimeScore, $arrData['User'][0]['age'], $arrData['User'][0]['gender']);
        
        // var_dump($arrData);
        
        $this->load->view('responses', $arrData); 
      
    }
    
    //export user data in csv file
    public function export()
    {
        $user_file_num = $_GET['file_num'];
        $user_id = $_GET['user_id'];
        
        $this->load->model('adminmodel');
        
        $arrResult = $this->adminmodel->FetchUserTestResult("pitch", $user_id);
        
          // var_dump($arrResult);
        // var_dump($user_id);
          // die;
         
        $arrTemp = array();
         $arrHeaders = array('Responses','Points','Practice Responses', 'Score','Certile');
            
        // $arrHeaders = array('ID', 'First Name', 'Last Name', 'Age', 'Gender', 'File Number', 'P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7');
        // foreach ($arrResult  as $key => $value) 
        // {
            if(count($arrResult['practice_result']) > 0)
            {
                foreach($arrResult['practice_result']  as $key => $value) {
                if($value['status'] == 1) {
                    $practiceintQt = 1;
                    foreach ($arrResult['practice_result'] as $qt) 
                    {
                        var_dump($qt);
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
            if(count($arrResult['test_result']) > 0)
            {
                foreach ($arrResult['test_result'] as $key => $qt) {
                    // var_dump($qt);
                    $arrResult['Answer '.$intQt] = $qt;
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
        
        
        $maxColumns = max(array_map(function($row){
                return count($row);
            }, $arrTemp));
        $this->cleanArray($arrTemp);
        
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
    }
        //print_r($arrTemp); exit;
        foreach ($arrTemp as $key => $value) 
        {
            $intScore = $this->adminmodel->FetchUserResult($user_id);
            $value['score'] = $intScore;
            $value['certile'] = $this->adminmodel->FetchAllCertileWRT($intScore, $value['age'], $value['gender']);
            // var_dump($value['certile']);
            // var_dump($value['score']);
            // die;
        }
        $arrHeaders[] = 'Score';
        $arrHeaders[] = 'Certile';
        $arrHeaders = array_unique($arrHeaders);
        
        // Enable to download this file
        $filename = "Responses.csv";
                
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