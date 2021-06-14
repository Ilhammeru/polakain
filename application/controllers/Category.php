<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Category
 * Category as 'Kategori'
 */
class Category extends CI_Controller {

	function __construct() {
		parent::__construct();

		// Check session
		$this->session_lib->check_session();
	}
	// End of function __construct();

    public function index() {

        $this->layout_lib->default_template('master/category/index');
    }
    // End of function index

    public function get_data_category() {

        $query = $this->db->query("SELECT name
                                          FROM itemcategory
                                          WHERE dept_id = " . $this->session->userdata('dept_id') . "
                                          ORDER BY name ASC")->result();

        echo json_encode($query);
    }
    // End of function get_data_category

    public function get_data_color() {

        $query = $this->db->query("SELECT name
                                          FROM itemcolor
                                          ORDER BY name ASC")->result();

        echo json_encode($query);
    }
    // End of function get_data_color

    /**
     * @param post => input_category
     */
    public function save_category() {

        $input_category = $this->input->post('input_category');

        if (empty($input_category)) {
            echo 'error-null';
        } else {

            $query = $this->db->query("SELECT id FROM itemcategory
                                            WHERE name = '" . $input_category . "'
                                            AND dept_id = " . $this->session->userdata('dept_id'));

            if ($query->num_rows() > 0) {

                echo 'error-duplicate';
            } else {

                $data = array(
                                'name' => $input_category,
                                'dept_id' => $this->session->userdata('dept_id')
                );

                $this->db->insert('itemcategory', $data);

                echo 'success';
            }
        }
    }
    // End of function save_category

    /**
     * @param post => input_color
     */
    public function save_color() {

        $input_color = $this->input->post('input_color');

        if (empty($input_color)) {
            echo 'error-null';
        } else {

            $query = $this->db->query("SELECT id FROM itemcolor
                                            WHERE name = '" . $input_color . "'");

            if ($query->num_rows() > 0) {

                echo 'error-duplicate';
            } else {

                $data = array(
                                'name' => $input_color
                );

                $this->db->insert('itemcolor', $data);

                echo 'success';
            }
        }
    }
    // End of function save_color

}
/* End of file Category.php */
/* Location: ./application/controllers/Category.php */