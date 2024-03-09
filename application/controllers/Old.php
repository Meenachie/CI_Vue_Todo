<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Old extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function index()
	{
		$this->load->view('index.php');
	}

    public function signup()
    {
        $this->load->view('signup.php');
    }

    public function login()
    {
        $this->load->view('login.php');
    }

    public function aflogin()
    {
        $this->load->view('home.php');
    }

    public function bfedit()
    {
        $this->load->view('update.php');
    }

    public function vprofile()
    {
        $this->load->view('profile.php');
    }

    public function vchange_password()
    {
        $this->load->view('change_password.php');
    }

}
?>