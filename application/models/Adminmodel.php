<?php
/**
* This class is used to handle the customer related info.
* Develope on 19th July'2016 by Hemanth Kumar
*/
class Adminmodel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	function check_login()
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
	
		
		
	

	function FetchQuestions()
	{
		$strQuery = 'SELECT * FROM pitch_questions Where questiontype = "test" AND show_or_hide = 1';

		$objQuery = $this->db->query($strQuery);

		if($objQuery->num_rows())
		{
			return $objQuery->result_array();
		}
		else
		{
			return array();
		}
	}

	function upload_question()
	{
		if(sizeof($_POST))
		{
			$serial_number = $_POST['serial_number'];
			$strQuestionCode = $_POST['questioncode'];
			$timestamp = time();

			$target_file1 = false;

			$strNewFileName = false;

			if($strQuestionCode)
			{
				$fileName = '';

				if($_FILES && $_POST['id'] == -1)
				{
					$target_dir = "uploads/";

			        $path = $target_dir.date('Ymd', $timestamp);
			        
			        if(!file_exists($path)) 
			        {
			        	$oldmask = umask(0);
			        	mkdir($path, 0777);
			        	umask($oldmask);
			        }

			        $fileInfo = pathinfo($_FILES["audioname"]["name"]);
			        
			        $strNewFileName = date('YmdHis', $timestamp).'.'.$fileInfo['extension'];
					
					$target_file = $path ."/". basename($_FILES["audioname"]["name"]);
				
			        $target_file1 = $path."/".$strNewFileName;
				
					if(!move_uploaded_file($_FILES["audioname"]["tmp_name"], $target_file1))
			        {
			    		return 0;
			        }
				}

				if($_POST['id'] == -1)
				{
					$arrData = array(
						'serial_number'  => $serial_number, 
						'questioncode'  => $strQuestionCode, 
						'questiontype'  => 'test', 
						'addeddate'	    => date('Y-m-d H:m:s', $timestamp),
						'audiopath'		=> $target_file1,
						'audiofilename' => $strNewFileName,
						'answer' 		=> $_POST['answer']
					);
				}
				else
				{
					$arrData = array(
						'serial_number'  => $serial_number, 
						'questioncode'  => $strQuestionCode, 
						'answer' 		=> $_POST['answer']
					);
				}

				if($_POST['id'] == -1)
				{
					$result = $this->db->insert('pitch_questions', $arrData);
					
					if($this->db->affected_rows()) 
					{
						//Push the new question id in sorted test questions order
						$new_id = $this->db->insert_id();
						
						$sql = 'SELECT * FROM pitch_questions_order WHERE type="questions"';
						$result = $this->db->query($sql);
						
						if($result->num_rows() > 0) 
						{
							$row = $result->row();
							$obj = unserialize($row->question_order);
							
							array_push($obj['test'], $new_id);
							
							$arrData = array(
									'question_order' => serialize($obj)
									);
							
							$this->db->where('type', 'questions');

							$this->db->update('pitch_questions_order', $arrData);
						} 
						
						$success = array(
							"success" => "success",
							"status" => "insert",
							"message" => "Inserted successfully."
						);
					}
					else
					{
						$success = array(
							"success" => "failed",
							"status" => "insert",
							"message" => "Something went wrong."
						);
					}
				}
				else
				{
					$this->db->where('id', $_POST['id']);

					$result = $this->db->update('pitch_questions', $arrData);
					
					if($this->db->affected_rows()) {
						$success = array(
							"success" => "success",
							"status" => "update",
							"message" => "Updated successfully."
						);
					}
					else
					{
						$success = array(
							"success" => "failed",
							"status" => "update",
							"message" => "Something went wrong."
						);
					}
				}
				
				return $success;
			}
		}
	}

	function FetchUsers()
	{
		$strQuery = 'SELECT * FROM users ORDER BY id DESC';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->result_array();

	}
	
	function fetch_user($file)
	{
		$strQuery = 'SELECT * FROM users WHERE filenumber='.$file;

		$objQuery = $this->db->query($strQuery);

		return $objQuery->result_array();

	}
	
	function FetchUserResult($id_user)
	{
		$arrTemp = $this->_userResults($id_user);

		$intCounter = 0;

		foreach ($arrTemp as $key => $value) {
			if($value['includeinscoring'] && ($value['optionid'] == $value['answer']))
			{
				$intCounter = $intCounter + 1;
			}
		}

		return $intCounter;
	}

	function FetchCertile()
	{
		$strQuery = 'SELECT * FROM aims_certile ORDER BY id DESC';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->result_array();
	}
	
	//Get the certile score based on user age, gender and score from pitch_certile_scores table in db
	function FetchCertileWRT($p_intScore, $age , $gender, $type)
	{
		$strQuery = 'SELECT age,score,certile FROM pitch_certile_scores WHERE gender = "'.$gender.'"';

		$objQuery = $this->db->query($strQuery);

		$temp = 0;
		// var_dump($objQuery);
		if($objQuery->num_rows())
		{
			//Get the certile score based on user age, gender and score
			foreach($objQuery->result_array() as $row) 
			
			{
				// var_dump($row);
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
	function FetchAllCertileWRT($p_intScore, $age , $gender)
	{
		$strQuery = 'SELECT age,score,certile FROM pitch_certile_scores WHERE gender = "'.$gender.'"';

		$objQuery = $this->db->query($strQuery);

		$temp = 0;
		// var_dump($objQuery);
		if($objQuery->num_rows())
		{
			//Get the certile score based on user age, gender and score
			foreach($objQuery->result_array() as $row) 
			
			{
				// var_dump($row);
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

	function _userResults($id_user)
	{
		
		$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "test" AND userid = '.$id_user.' ORDER BY q.serial_number';

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
	
	function _userPracticeResults($id_user)
	{
		$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$id_user;

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
	//
	/*function _userPracticeResults($id_user)
	{
		$strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$id_user;

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
	*/

	//Get all users practice and test question results
	
	function FetchResult()
	{
		// var_dump($type); 
		
		$arrUsers = $this->FetchUsers();
		foreach ($arrUsers as $key => &$value) 
		{
			$value['test_result'] = $this->_userResults($value['id']);
			$value['practice_result'] = $this->_userPracticeResults($value['id']);
		}
		return $arrUsers;
	}
	
	
	function FetchTestResult($type)
	{		
		$arrUsers = $this->FetchUsers();
		if($type == "pitch") {
			foreach ($arrUsers as $key => &$value) 
			{
				$value['test_result'] = $this->_userResults($value['id']);
				$value['practice_result'] = $this->_userPracticeResults($value['id']);
			}
		} else {
			foreach ($arrUsers as $key => &$value) 
			{
				$value['test_result'] = $this->_userResults($value['id']);
				$value['practice_result'] = $this->_userPracticeResults($value['id']);
			}
		/*} elseif($type == "tonal") {
			foreach ($arrUsers as $key => &$value) 
			{
				$value['test_result'] = $this->_userResults($value['id']);
				$value['practice_result'] = $this->_userPracticeResults($value['id']);
			}*/
		}
		
		return $arrUsers;
	}
	
	function _userPitchResults($user_id)
	{
		
		// $strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "test" AND userid = '.$user_id.' ORDER BY q.serial_number';
		$strQuery = 'SELECT ua.questionid , ua.optionid , q.answer , q.includeinscoring, h.status FROM pitch_user_answers ua INNER JOIN pitch_questions q  on ua.questionid = q.id INNER JOIN users h  on q.id = h.id WHERE q.questiontype = "test" AND userid= '.$user_id.' ';

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
	function _userTimeResults($user_id)
	{
		
		 $strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM time_user_answers ua INNER JOIN time_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "test" AND userid = '.$user_id.' ORDER BY q.serial_number';
		// $strQuery = 'SELECT ua.questionid , ua.optionid , q.answer , q.includeinscoring, h.status FROM pitch_user_answers ua INNER JOIN pitch_questions q  on ua.questionid = q.id INNER JOIN users h  on q.id = h.id WHERE q.questiontype = "test" AND userid= '.$user_id.' ORDER BY q.serial_number';

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
	
	function _userPitchPracticeResults($user_id)
	{
		// $strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM pitch_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$user_id;
		$strQuery = 'SELECT ua.questionid , ua.optionid , q.answer , q.includeinscoring, h.status FROM pitch_user_answers ua INNER JOIN pitch_questions q  on ua.questionid = q.id INNER JOIN users h  on q.id = h.id WHERE q.questiontype = "practice" AND userid= '.$user_id.' ';
		
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
	function _userTimePracticeResults($user_id)
	{
		// $strQuery = 'SELECT ua.`questionid`, ua.`optionid`, q.`answer`, q.includeinscoring FROM time_user_answers ua INNER JOIN pitch_questions q ON q.id = ua.`questionid` WHERE q.questiontype = "practice" AND userid = '.$user_id;
		$strQuery = 'SELECT ua.questionid , ua.optionid , q.answer , q.includeinscoring, h.status FROM time_user_answers ua INNER JOIN time_questions q  on ua.questionid = q.id INNER JOIN users h  on q.id = h.id WHERE q.questiontype = "practice" AND userid= '.$user_id.'';

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
	
	function FetchUserTestResult($type, $user_id)
	{	
		// $arrUsers = $this->FetchUsers();	
	   //$arrUsers = $this->fetch_user($file);
		if($type == "pitch") {
			$value['test_result'] = $this->_userPitchResults($user_id);
			$value['practice_result'] = $this->_userPitchPracticeResults($user_id);
			 // var_dump($value);
		} elseif($type == "time") {
			$value['test_result'] = $this->_userTimeResults($user_id);
			$value['practice_result'] = $this->_userTimePracticeResults($user_id);
			 // var_dump($value);
		} elseif($type == "tonal") {
			$value['test_result'] = $this->_userTimeResults($user_id);
			$value['practice_result'] = $this->_usertTimePracticeResults($user_id);
		}
		// var_dump($value);
		
		return $value;
	}
	
	
	
	function FetchPitchUserResult($user_id)
	{
		$arrTemp = $this->_userPitchResults($user_id);

		$intCounter = 0;

		foreach ($arrTemp as $key => $value) {
			if($value['includeinscoring'] && ($value['optionid'] == $value['answer']))
			{
				$intCounter = $intCounter + 1;
			}
			// var_dump($value);
		}

		return $intCounter;
	}
	function FetchTimeUserResult($user_id)
	{
		$arrTemp = $this->_userTimeResults($user_id);

		$intCounter = 0;

		foreach ($arrTemp as $key => $value) {
			if($value['includeinscoring'] && ($value['optionid'] == $value['answer']))
			{
				$intCounter = $intCounter + 1;
			}
		}

		return $intCounter;
	}
	
	function UpdateIncludeInScore()
	{
		$id = $_POST['questionid'];

		$status = $_POST['includeinscore'];

		if($id)
		{
			$arrData = array(
                'includeinscoring' => $status
            );

	 		$this->db->where('id', $id);

			$this->db->update('pitch_questions', $arrData);
		}
		else
		{
			return false;
		}
	}
	
	function fetch_certile_scores()
	{
		$strQuery = 'SELECT * FROM pitch_certile_scores ORDER BY id DESC';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->result_array();
	}
	
	function edit_or_add_certilescores()
	{
		$id = $_POST['id'];
		$age = $_POST['age'];
		$gender = $_POST['gender'];
		$score = $_POST['score'];
		$certile = $_POST['certile'];
		
		$arrData = array(
                'age' => $age,
                'gender' => $gender,
                'score' => $score,
                'certile' => $certile
            );

		if($id == "")
		{
			//insert row
			$this->db->insert('pitch_certile_scores', $arrData);
			
			if($this->db->affected_rows()) {
				$success = array(
					"success" => "success",
					"status" => "insert",
					"message" => "Inserted successfully."
				);
			}
			else 
			{
				$success = array(
					"success" => "failed",
					"status" => "insert",
					"message" => "Something went wrong."
				);
			}
		}
		else
		{	
			//update the row
	 		$this->db->where('id', $id);

			$this->db->update('pitch_certile_scores', $arrData);
			
			if($this->db->affected_rows()) 
			{
				$success = array(
					"success" => "success",
					"status" => "update",
					"message" => "Updated successfully."
				);
			}
			else 
			{
				$success = array(
					"success" => "failed",
					"status" => "update",
					"message" => "Something went wrong."
				);
			}
		}
		return $success;
	}
	
	function delete_certile_score_row()
	{
		$id = $_POST['id'];

		if($id)
		{
			$this->db->where('id', $id);
			$this->db->delete('pitch_certile_scores');
			
			if($this->db->affected_rows()) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	// Change the active status in pitch_questions table.
	function inactive_question()
	{
		$id = $_POST['questionid'];

		$status = $_POST['active'];

		if($id)
		{
			$arrData = array(
                'active' => $status
            );

	 		$this->db->where('id', $id);

			$this->db->update('pitch_questions', $arrData);
			
			if($this->db->affected_rows()) 
			{
				//Push the new question id in sorted test questions order
				$new_id = $this->db->insert_id();
				
				$sql = 'SELECT * FROM pitch_questions_order WHERE type="questions"';
				$result = $this->db->query($sql);
				
				if($result->num_rows() > 0) 
				{
					$row = $result->row();
					$obj = unserialize($row->question_order);
					
					if($status == 1) 
					{
						array_push($obj['test'], $id);
					} 
					else 
					{
						foreach($obj['test'] as $key => $value) 
						{
							if($value == $id) 
							{
								//delete the element of question
								array_splice($obj['test'], $key, 1);
							}
						}
					}
					
					$arrData = array(
							'question_order' => serialize($obj)
							);
					
					$this->db->where('type', 'questions');

					$this->db->update('pitch_questions_order', $arrData);
				} 
			}
		}
		else
		{
			return false;
		}
	}
	
	// Get the practice and test questions from pitch_questions table in db.
	function fetch_questions() 
	{
		
		$sql = 'SELECT * FROM pitch_questions_order WHERE type="questions"';

		$result = $this->db->query($sql);
		
		// Check the pitch_questions_order table have sorted questions or not.
		if($result->num_rows() > 0) 
		{
			$row = $result->row();
			
			// $obj = unserialize(base64_decode($row->question_order));
			$obj = unserialize($row->question_order);
			
			$array['order'] = $obj;
		}
			
		/* 
		//Get the practice test questions
		$strQuery = 'SELECT id,questioncode,audiofilename FROM pitch_questions WHERE questiontype="practice" and active = 1';

		$practiceQuery = $this->db->query($strQuery);
		
		$array['practice'] = $practiceQuery->result_array();
		*/
		
		//Get the test questions
		$query = 'SELECT id,serial_number,audiofilename FROM pitch_questions WHERE questiontype="test" and active = 1';

		$testQuery = $this->db->query($query);
		
		$array['test'] = $testQuery->result_array();
		
		return $array;	
	}
	
	// save the questions order in pitch_questions_order table in db.
	function save_questions_order() 
	{
		// $question_order = base64_encode(serialize($_POST['question_order']));
		$question_order = serialize($_POST['question_order']);

		if($question_order)
		{	
			$sql = "SELECT * FROM pitch_questions_order";
			$result = $this->db->query($sql);
			
			if($result->num_rows() > 0) 
			{
				$arrData = array(
						'question_order' => $question_order
						);
				
				$this->db->where('type', 'questions');

				$this->db->update('pitch_questions_order', $arrData);
				
			} 
			else 
			{
				$arrData = array(
							'type' => 'questions',
							'question_order' => $question_order,
						   );
				
				$this->db->insert('pitch_questions_order', $arrData);
			}
			
			if($this->db->affected_rows())
			{
				return "success";
			} 
			else 
			{
				return "fail";
			}
		}
	}
	
	//fetch subscores data to display in admin subscores view table
	function fetch_subscores()
	{
		$strQuery = 'SELECT * FROM pitch_subscores ORDER BY id DESC';

		$objQuery = $this->db->query($strQuery);

		return $objQuery->result_array();
	}
	
	//Update subscores row data in db
	function update_subscores()
	{
		$id = $_POST['id'];
		$questions = $_POST['questions'];
		$score_range = $_POST['score_range'];
		
		$arrData = array(
                'questions' => $questions,
                'score_range' => $score_range,
            );

		//update the row
		// $this->db->where('id', $id);
	if($id == "")
	{
		//insert row
		$this->db->insert('pitch_subscores', $arrData);
		
		if($this->db->affected_rows()) 
		{
			$success = array(
				"success" => "success",
				"status" => "Inserted",
				"message" => "Inserted successfully."
			);
		}
		else 
		{
			$success = array(
				"success" => "failed",
				"status" => "Inserted",
				"message" => "Something went wrong."
			);
		}
		
	}
	else {
		$this->db->where('id', $id);

			$this->db->update('pitch_subscores', $arrData);
			
			if($this->db->affected_rows()) 
			{
			$success = array(
				"success" => "success",
				"status" => "update",
				"message" => "Updated successfully."
			);
		}
		else 
		{
			$success = array(
				"success" => "failed",
				"status" => "update",
				"message" => "Something went wrong."
			);
		}
		
	}
		return $success;
}
	
	
	// Change the subscore status in pitch_subscores table.
	function update_subscores_status()
	{
		$id = $_POST['rowId'];

		$status = $_POST['active'];

		if($id)
		{
			$arrData = array(
                'subscore_status' => $status
            );

	 		$this->db->where('id', $id);

			$this->db->update('pitch_subscores', $arrData);
			
			if($this->db->affected_rows()) 
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	// deleteing add subscore row in subscore table
	function delete_subscore_row()
	{
		$id = $_POST['id'];

		if($id)
		{
			$this->db->where('id', $id);
			$this->db->delete('pitch_subscores');
			
			if($this->db->affected_rows()) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function FetchPitchCertileWRT($p_intScore, $age , $gender)
	{
		$strQuery = 'SELECT age,score,certile FROM pitch_certile_scores WHERE gender = "'.$gender.'"';

		$objQuery = $this->db->query($strQuery);

		$temp = 0;
		// var_dump($objQuery);
		if($objQuery->num_rows())
		{
			//Get the certile score based on user age, gender and score
			foreach($objQuery->result_array() as $row) 
			
			{
				// var_dump($row);
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
	
	function FetchTitchCertileWRT($p_intScore, $age , $gender)
	{
		$strQuery = 'SELECT age,score,certile FROM time_certile_scores WHERE gender = "'.$gender.'"';

		$objQuery = $this->db->query($strQuery);

		$temp = 0;
		// var_dump($objQuery);
		if($objQuery->num_rows())
		{
			//Get the certile score based on user age, gender and score
			foreach($objQuery->result_array() as $row) 
			
			{
				// var_dump($row);
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
	function FetchTimeCertileWRT($p_intScore, $age , $gender)
	{
		$strQuery = 'SELECT age,score,certile FROM time_certile_scores WHERE gender = "'.$gender.'"';

		$objQuery = $this->db->query($strQuery);

		$temp = 0;
		// var_dump($objQuery);
		if($objQuery->num_rows())
		{
			//Get the certile score based on user age, gender and score
			foreach($objQuery->result_array() as $row) 
			
			{
				// var_dump($row);
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
	
	
	function  checkbox()
	{
		$subscore_check = $_POST['active'];

		$arrData = array(
			'subscore_check' => $subscore_check
		);

		$this->db->where('id', 1);

		$this->db->update('pitch_subscore_checkbox', $arrData);
		
		if($this->db->affected_rows()) 
		{
			return true;
		} else {
			return false;
		}
	}
	
	function fetch_subscores_status() {
		
		$query = "select subscore_check from pitch_subscore_checkbox where id= 1";
		
		$objQuery = $this->db->query($query);

		return $objQuery->row_array();
		
	}
	
}

?>
