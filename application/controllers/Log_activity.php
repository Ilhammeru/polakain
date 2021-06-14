<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Log_activity
 * Log_activity as 'Aktifitas User'
 */
class Log_activity extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_log_activity');

		// Log
		$this->log_activity_lib->activity_record('Log Activity', 'Visit');

		$attr = array(
						'filterUsers' 	=> $this->display_filter_users(),
						'filterAccess' 	=> $this->display_filter_access()
					);

		$this->layout_lib->default_template('log_activity/report-data', $attr);
		
	}
	// End of function report_data

	/**
	 * @return string
	 */
	public function display_filter_users() {

		// Get username by dept
		$users = $this->db->query("SELECT DISTINCT users.id AS user_id, users.username 
										  FROM log_activity 
										  JOIN users ON users.id = log_activity.user_id 
										  WHERE log_activity.dept_id = '" . $this->session->userdata('dept_id') . "'
										  ORDER BY users.username ASC")->result();

		$html = '<select class="form-control select2" multiple><option disabled>Cari User</option>';

		foreach ($users as $row) :

			$html .= '<option value="' . $row->user_id . '">' . $row->username . '</option>';

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_users

	/**
	 * @return string
	 */
	public function display_filter_access() {

		// Get access_name by dept
		$logs = $this->db->query("SELECT DISTINCT access_name
										 FROM log_activity 
										 WHERE log_activity.dept_id = '" . $this->session->userdata('dept_id') . "'
										 ORDER BY access_name ASC")->result();

		$html = '<select class="form-control select2 select2-purple" multiple><option disabled>Cari Menu</option>';

		foreach ($logs as $row) :

			$html .= '<option>' . $row->access_name . '</option>';

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_access

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
		$columnOrder = array('log_activity.time', 'log._activity.log', 'users.username', 'log_activity.access_name');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=> 'timestamp',
    							'field' 	=> 'log_activity.time',
    							'type' 		=> 'daterange'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'log_activity.log',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'integer',
    							'field' 	=> 'users.id',
    							'type' 		=> 'select-multiple'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'log_activity.access_name',
    							'type' 		=> 'select-multiple'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('log_activity.updated_time' => 'desc');

    	$countTotal = "SELECT id
    						  FROM log_activity
    						  WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

    	// Query
    	$query = "SELECT log_activity.time,
    					 log_activity.log,
    					 users.username,
    					 log_activity.access_name
    					 FROM log_activity
    					 JOIN users ON users.id = log_activity.user_id ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'log_activity');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$users = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($users)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = date_format(date_create($rows->time), 'd M Y H:i:s');

			$row[] = $rows->log;

			$row[] = $rows->username;

			$row[] = $rows->access_name;

			$data[] = $row;

		endforeach;

		// Results
		$output = array(
						"draw" 				=> $_POST['draw'],
            			"recordsTotal" 		=> $countTotal,
            			"recordsFiltered" 	=> $this->db->query($query)->num_rows(),
						"data" 				=> $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data

}
/* End of file Log_activity.php */
/* Location: ./application/controllers/Log_activity.php */
