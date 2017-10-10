<?php
/**
* This class is used to handle the customer related info.
*/
class Adminmodel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	function Login()
	{
		if(sizeof($_POST) > 0)
		{
			$strUserName = $_POST['userame'];

			$strPassword = md5($_POST['password']);

			$strQuery = 'SELECT * FROM employees WHERE username LIKE "'.$strUserName.'"';

			$objQuery = $this->db->query($strQuery);

			if($objQuery->num_rows() > 0)
			{
				$arrResult = $objQuery->result_array();

				$arrEmployee = $arrResult[0];

				if($arrEmployee['passwd'] == $strPassword && $arrEmployee['active'] == 1)
				{
					$this->session->set_userdata('EmployeeID', $arrEmployee['id']);

					$this->session->set_userdata('EmployeeFName', $arrEmployee['firstname']);

					$this->session->set_userdata('EmployeeLName', $arrEmployee['lastname']);
					
					$this->session->set_userdata('EmployeeRole', $arrEmployee['role']);

					return 1;
				}
				elseif($arrEmployee['passwd'] != $strPassword)
				{
					$this->session->set_flashdata('Errors', 'Password is not matching with the records.');
					return 0;
				}
				elseif($arrEmployee['active'] !== 1)
				{
					$this->session->set_flashdata('Errors', 'User is not active.');
					return 0;
				}
			}
			else
			{
				$this->session->set_flashdata('Errors', 'User is not registered with us. Please check username entered.');
				return 0;
			}
		}
	}

	function FetchUsers($type)
	{
		if($type == "pitch")
		{
			$strQuery = 'SELECT * FROM users WHERE pitch_completed_date <> "0000-00-00 00:00:00.000000" ORDER BY id DESC';
		}
		else if($type == "time")
		{
			$strQuery = 'SELECT * FROM users WHERE time_completed_date <> "0000-00-00 00:00:00.000000" ORDER BY id DESC';
		}
		else if ($type == "tonal")
		{
			$strQuery = 'SELECT * FROM users WHERE tonal_completed_date <> "0000-00-00 00:00:00.000000" ORDER BY id DESC';
		}

		$objQuery = $this->db->query($strQuery);

		return $objQuery->result_array();
	}
	
	function fetch_user($file_num)
	{
		$strQuery = 'SELECT * FROM users WHERE filenumber="'.$file_num.'"';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->row_array();
	}
	
	function FetchUserResult($id_user, $type)
	{
		$app_type = $type;
		
		$arrTemp = $this->_userResults($id_user, $app_type);

		$intCounter = 0;

		foreach ($arrTemp as $key => $value) {
			if($value['includeinscoring'] && ($value['optionid'] == $value['answer']))
			{
				$intCounter = $intCounter + 1;
			}
		}

		return $intCounter;
	}

	//Get the certile score based on user age, gender and score from pitch_certile_scores table in db
	function FetchCertileWRT($p_intScore, $age , $gender, $type)
	{
		if($type == "pitch") 
		{
			$strQuery = 'SELECT age,score,certile FROM pitch_certile_scores WHERE gender = "'.$gender.'"';
		}
		else if($type == "time") {
			$strQuery = 'SELECT age,score,certile FROM time_certile_scores WHERE gender = "'.$gender.'"';
		}
		else if($type == "tonal") {
			$strQuery = 'SELECT age,score,certile FROM tonal_certile_scores WHERE gender = "'.$gender.'"';
		}

		$objQuery = $this->db->query($strQuery);

		$temp = 0;
		
		if($objQuery->num_rows())
		{
			//Get the certile score based on user age, gender and score
			foreach($objQuery->result_array() as $row) 
			{
				//Explode the certile age and score for checking in between the user age and score. 
				$certile_age = explode("-",$row['age']);
				$certile_score = explode("-",$row['score']);
				
				if($age == $certile_age['0'] || $age >= $certile_age['0'] && $age <= $certile_age['1']) 
				{
					if($p_intScore == $certile_score['0'] || $p_intScore >= $certile_score['0'] && $p_intScore <= $certile_score['1']) 
					{
						$temp = $row['certile'];
						break;
					} 
				}
			}
		}
		return $temp;
	} 

	function _userResults($id_user, $type)
	{
		if($type == "pitch") {
			$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "test" AND userid = '.$id_user.' ORDER BY q.serial_number';
		} else if($type == "time") {
			$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM time_user_answers ua INNER JOIN time_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "test" AND userid = '.$id_user.' ORDER BY q.serial_number';
		} else if($type == "tonal") {
			$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM tonal_user_answers ua INNER JOIN tonal_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "test" AND userid = '.$id_user.' ORDER BY q.serial_number';
		}

		$objQuery = $this->db->query($strQuery);

		if($objQuery->num_rows() > 0)
		{
			return $objQuery->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function _userPracticeResults($id_user, $type)
	{
		if($type == "pitch")
		{
			$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$id_user;
		}
		else if($type == "time") 
		{
			$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM time_user_answers ua INNER JOIN time_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$id_user;
		}
		else if($type == "tonal") 
		{
			$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM tonal_user_answers ua INNER JOIN tonal_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$id_user;
		}

		$objQuery = $this->db->query($strQuery);

		if($objQuery->num_rows() > 0)
		{
			return $objQuery->result_array();
		}
		else
		{
			return array();
		}
	}

	//Get all users practice and test question results
	function FetchTestResult($type)
	{
		$arrUsers = $this->FetchUsers($type);
		
		foreach ($arrUsers as $key => &$value) 
		{
			$value['test_result'] = $this->_userResults($value['id'], $type);
			
			$value['practice_result'] = $this->_userPracticeResults($value['id'], $type);
		}
		
		return $arrUsers;
	}
	
	function fetch_pitch_user($file_num)
	{
		$strQuery = 'SELECT id,firstname,lastname,age,gender,filenumber,addeddate,pitch_completed_date as completeddate,active,pitch_status as status FROM users WHERE filenumber="'.$file_num.'"';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->row_array();
	}
	
	function fetch_time_user($file_num)
	{
		$strQuery = 'SELECT id,firstname,lastname,age,gender,filenumber,addeddate,time_completed_date as completeddate,active,time_status as status FROM users WHERE filenumber="'.$file_num.'"';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->row_array();
	}
	
	//Get user practice and test question results for single user scores result
	function fetch_user_test_results($file_num)
	{
		$arrUser = array();
		$pitchArrUser = $this->fetch_pitch_user($file_num);
		$pitchArrUser['type'] = "Pitch Discrimination";
		$pitchArrUser['app_type'] = "pitch";
		
		$timeArrUser = $this->fetch_time_user($file_num);
		$timeArrUser['type'] = "Time Discrimination";
		$timeArrUser['app_type'] = "time";
		
		/*
		$tonalArrUser = $this->fetch_time_user($file_num);
		$tonalArrUser['type'] = "Tonal Memory";
		$tonalArrUser['app_type'] = "time";
		*/
		
		array_push($arrUser, $pitchArrUser, $timeArrUser);
		
		return $arrUser;
	}
	
	//Get user practice and test question results for single user responses result
	function fetch_user_test_result($file_num)
	{
		$arrUser = array();
		$pitchArrUser = $this->fetch_pitch_user($file_num);
		$pitchArrUser['type'] = "Pitch Discrimination";
		$pitchArrUser['app_type'] = "pitch";
		$pitchArrUser['test_result'] = $this->_userResults($pitchArrUser['id'], "pitch");
		$pitchArrUser['practice_result'] = $this->_userPracticeResults($pitchArrUser['id'], "pitch");
		
		$timeArrUser = $this->fetch_time_user($file_num);
		$timeArrUser['type'] = "Time Discrimination";
		$timeArrUser['app_type'] = "time";
		$timeArrUser['test_result'] = $this->_userResults($timeArrUser['id'], "time");
		$timeArrUser['practice_result'] = $this->_userPracticeResults($timeArrUser['id'], "time");
		
		/*
		$tonalArrUser = $this->fetch_time_user($file_num);
		$tonalArrUser['type'] = "Tonal Memory";
		$tonalArrUser['app_type'] = "time";
		$tonalArrUser['test_result'] = $this->_userResults($tonalArrUser['id'], "time");
		$tonalArrUser['practice_result'] = $this->_userPracticeResults($tonalArrUser['id'], "time");
		*/
		
		array_push($arrUser, $pitchArrUser, $timeArrUser);
		
		return $arrUser;
	}
	
}

?>
