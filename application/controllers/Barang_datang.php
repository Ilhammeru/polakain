<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Barang Datang
 */
class Barang_datang extends CI_Controller {

	private $closingDate;

	function __construct() {
		parent::__construct();

		// Check session
		$this->session_lib->check_session();
		$this->closingDate = lock_closing('Invoice');
	}
	// End of function __construct();

    public function index()  {

        $data = array(
						'vendor' 	=> $this->db->query("SELECT id, name 
																FROM vendor 
																WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																ORDER BY name ASC")->result(),
                        'warehouse' => $this->db->query("SELECT id, name
                                                                FROM warehouse
																WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																ORDER BY name ASC")->result(),
                        'category'  => $this->db->query("SELECT id, name
                                                                FROM itemcategory
																WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																ORDER BY name ASC")->result(),
                        'color'     => $this->db->query("SELECT id, name
                                                                FROM itemcolor
                                                                ORDER BY name ASC")->result()
        );

        $this->layout_lib->default_template('transaction/barang_datang/index', $data);
    }
    // End of function index

}
/* End of file Barang_datang.php */
/* Location: ./application/controllers/Barang_datang.php */
