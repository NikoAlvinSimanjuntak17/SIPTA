<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Dashboard Controller
*| --------------------------------------------------------------------------
*| For see your board
*|
*/
class Dashboard extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (!$this->aauth->is_loggedin()) {
			redirect('administrator/login','refresh');
		}

		$data = [];
		$this->render('backend/standart/dashboard', $data);
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */