<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Template_pola
 */
class Template_pola extends CI_Controller {

	function __construct() {
		parent::__construct();

		// Check session
		$this->session_lib->check_session();
	}
	// End of function __construct();

    public function index() {

        $attr = array();
		$this->layout_lib->default_template('master/template_pola/index', $attr);
    }
    // End of function index

    /**
     * @param post => template_name
     * @param post => template_qty
     * @param post => template_price
     */
    public function submit_data()  {

        $template_name  = strtoupper($this->input->post('template_name'));
        $template_qty   = $this->input->post('template_qty');
        $template_plastik   = $this->input->post('template_plastik');
        $template_price = str_replace(',', '', $this->input->post('template_price'));

        $checkDuplicate = $this->db->query("SELECT id 
                                                FROM template_pola 
                                                WHERE name = '$template_name'")->num_rows();

        if ($checkDuplicate > 0) {
            echo 'error-duplicate';
        } else {

            $data = array(
                            'name'  => $template_name,
                            'qty'   => $template_qty,
                            'qty_plastik'   => $template_plastik,
                            'price' => $template_price
            );

            $this->db->insert('template_pola', $data);

            echo 'success';
        }
    }   
    // End of function submit_data

    public function get_data() {

        $query = $this->db->query("SELECT id, name, qty, price, is_active, qty_plastik
                                        FROM template_pola
                                        ORDER BY id DESC")->result();

        echo json_encode($query);
    }
    // End of function get_data

    /**
     * @param post => id
     * @param post => status
     */
    public function change_status() {

        $id     = $this->input->post('id');
        $status = $this->input->post('status');

        $data['is_active'] = $status;

        $this->db->where('id', $id);
        $this->db->update('template_pola', $data);
    }
    // End of functionn change_status
}

/* End of file Template_pola.php */
/* Location: ./application/controllers/Template_pola.php */
