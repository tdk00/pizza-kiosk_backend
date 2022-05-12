<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 * @property  admin_user_model $AdminUserModel
 */
class Admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('admin/user/admin_user_model', 'AdminUserModel');

	}

	public function login()
	{

		if ( $this->session->userdata('logged_in') )
		{

			if( $this->session->userdata('role') == "admin" )
			{
				redirect('/categories_admin/');
			}
			else if( $this->session->userdata('role') == "kitchen" )
			{
				redirect('/kitchen_admin/');
			}
		}
		$this->load->view('admin/user/login');
	}

	public function sign_in()
	{

		if ( ! $this->session->userdata('logged_in') )
		{
			if( !empty( $this->input->post('username') ) && !empty( $this->input->post('password') ) )
			{

				$username = $this->input->post('username');
				$password = $this->input->post('password');
//				$this->addUser($username, $password);
				$user_info = $this->AdminUserModel->check_user( $username, $this->hashPassword($password, $username) );

				if( count( $user_info ) > 0 )
				{
					$data = array(
						'username'  => $user_info[0]['username'],
						'role' => $user_info[0]['role'],
						'logged_in'  => TRUE

					);
					$this->session->set_userdata($data);

					echo  json_encode( ["status"=>TRUE, "message"=> "Yönləndirilir" ] );

				}
				else
				{
					echo  json_encode( ["status"=>FALSE, "message"=> "Giriş məlumatları yalnışdır" ] );
				}


			}
			else
			{
				echo  json_encode( ["status"=>FALSE, "message"=> "Məlumatları tam doldurun" ] );
			}
		}
		//$this->load->view('admin/user/login');
	}

	public function logout()
	{
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('role');
		redirect('/admin/login');
	}

//	private function addUser($username, $password) {
//		$password = $this->hashPassword($password, $username);
//		$dataIns = array(
//			'username' => $username
//		, 	'password' => $password
//		);
//		if ($this->db->insert('users', $dataIns)) return $this->db->insert_id();
//		return FALSE;
//	}

	private function hashPassword($pass, $salt=FALSE) {

		if (!empty($salt)) $pass = $salt . implode($salt, str_split($pass, floor(strlen($pass)/2))) . $salt;
		return md5( $pass );
	}
}
