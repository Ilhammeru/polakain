<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Role
 * Role as 'Hak Akses'
 */
class Role extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_role_report');

		// Log
		$this->log_activity_lib->activity_record('Hak Akses', 'Visit');

		$this->layout_lib->default_template('user_mg/role/report-data');
		
	}
	// End of function report_data

	public function form_new_role() {
		
		$this->layout_lib->default_template('user_mg/role/form-new-role');

	}
	// End of function form_new_role

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	/**
	 * @param $columnOrder
	 * @param $columnSearch
	 * @param $order
	 */
	public function server_side_data() {

		// Set field order column
		$columnOrder = array('role.name', '');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'role.name',
    							'type' 		=> 'search'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('role.name' => 'asc');

    	// Query
    	$query = "SELECT id,
    	   				 name
    	   				 FROM role ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'role');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$role = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($role)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->name;

			$btnView = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-info disabled"><i class="fa fa-eye"></i></a>';

			$btnEdit = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-warning disabled"><i class="fa fa-edit"></i></a>';

			$btnDelete = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger disabled"><i class="fa fa-trash"></i></a>';

			$row[] = '<div class="btn-group">' . $btnView . $btnEdit . $btnDelete . '</div>';

			$data[] = $row;

		endforeach;

		// Results
		$output = array(
						"draw" 				=> $_POST['draw'],
            			"recordsTotal" 		=> $this->db->from('role')->count_all_results(),
            			"recordsFiltered" 	=> $this->db->query($query)->num_rows(),
						"data" 				=> $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data

}
/* End of file Role.php */
/* Location: ./application/controllers/Role.php/ */