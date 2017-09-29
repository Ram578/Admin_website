<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * This is TonalTest page controller.
	 * Develope on 21th July'2016 by Hemanth Kumar
	 */
	public function index()
	{
		if(!isset($this->session->userdata['EmployeeID']))
		{
			$this->load->view('login');
	
		
		}else
		{
			$arrData['Title'] = 'AIMs - Admin panel';

			$Header = $this->load->view('header', $arrData,true);

			$arrData['Header'] = $Header;

			$arrData['Footer'] = $this->load->view('footer', $arrData,true);

			$this->load->view('login', $arrData);
		}
	}


	 public function check_user() 
	 {
		$this->load->model('adminmodel');
		
		$result = $this->adminmodel->check_login();
 
		 if($result)
		{
			redirect('testing', 'refresh');
		}else
		{ 
			// $this->session->set_flashdata('Errors', array($result)); 
    		 redirect('login', 'refresh');
		}
	} 
}
