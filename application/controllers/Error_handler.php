<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Error_handler
 */
class Error_handler extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct

	public function index() {

		$this->layout_lib->default_template('layouts/error');
	}
	// End of function index

}
/* End of file Error_handler.php */
/* Location: ./application/controllers/Error_handler.php */
