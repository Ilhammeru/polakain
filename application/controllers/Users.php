<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Users
 */
class Users extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_user_report');

		// Log
		$this->log_activity_lib->activity_record('Users', 'Visit');

		$attr = array(
						'filterRole' => $this->display_filter_role()
					);

		$this->layout_lib->default_template('user_mg/users/report-data', $attr);
		
	}
	// End of function report_data

	public function form_change_password() {

		// Log empty

		$this->layout_lib->default_template('user_mg/users/form-change-password');
		
	}
	// End of function form_change_password

	public function form_new_user() {

		// Permission
		$this->session_lib->check_permission('p_user_add');

		// Log
		$this->log_activity_lib->activity_record('Add Users', 'Visit');

		$attr = array(
						'dept' => $this->db->query('SELECT id, name FROM ansena_department ORDER BY sort ASC')->result(),
						// Get role by dept
						'role' => $this->db->query("SELECT id, 
														   name 
														   FROM role 
														   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
														   ORDER BY name ASC")->result()
					);

		$this->layout_lib->default_template('user_mg/users/form-new-user', $attr);
		
	}
	// End of function form_new_user

	public function display_filter_role() {

		// Get role by dept
		$role = $this->db->query("SELECT id, 
										 name 
										 FROM role 
										 WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
										 ORDER BY name ASC")->result();

		$html = '<select class="form-control select2" multiple><option disabled>Cari Hak Akses</option>';

		foreach ($role as $row) :

			$html .= '<option value="' . $row->id . '">' . $row->name . '</option>';

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_role

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
		$columnOrder = array('users.username', 'users.dept_id', 'role.name', '');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'users.username',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'integer',
    							'field' 	=> 'role.id',
    							'type' 		=> 'select-multiple'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('users.username' => 'asc');

    	// Query
    	$query = "SELECT users.dept_id,
    					 users.id AS user_id, 
		   				 users.username AS username, 
		   				 role.name AS role_name
			   			 FROM users
			   			 JOIN role ON role.id = users.role_id ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'users');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$users = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($users)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->username;

			$row[] = $rows->dept_id;

			$row[] = $rows->role_name;

			if ($this->session->userdata('p_user_delete') == 1) {

				$btnDelete = '<a href="javascript:void(0)" id="btn-confirm-delete" key="' . $rows->user_id. '" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>';

			} else {

				$btnDelete = '';

			}

			$row[] = $btnDelete;

			$data[] = $row;

		endforeach;

		// Results
		$output = array(
						"draw" 				=> $_POST['draw'],
            			"recordsTotal" 		=> $this->db->from('users')->count_all_results(),
            			"recordsFiltered" 	=> $this->db->query($query)->num_rows(),
						"data" 				=> $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data

	/**
	 * @param $id
	 */
	public function load_modal_confirm_delete($id) {

		$attr = array('id' => $id);
		$this->load->view('user_mg/users/modal-confirm-delete', $attr);
		
	}
	// End of function load_modal_confirm_delete

	/**
	 * @param post => user_password
	 */
	public function save_new_password() {

		if (! empty($this->input->post('user_password'))) {

			$data = array(
							'password' 		=> md5(md5($this->input->post('user_password'))),
							'updated_time' 	=> date('Y-m-d H:i:s'),
							'updated_by' 	=> $this->session->userdata('user_id')
						);

			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('users', $data);

			$this->log_activity_lib->activity_record('Users', 'Change Password');

			echo 'success';

		} else {

			echo 'error';

		}
		
	}
	// End of function save_new_password

	/**
	 * @param post => username
	 * @param post => role
	 * @param post => user_password
	 */
	public function save_new_user() {

		// Permission
		$this->session_lib->check_permission('p_user_add');

		// is post null
		if (empty($this->input->post('username')) || 
			//empty($this->input->post('dept')) ||
			empty($this->input->post('user_password')) || 
			empty($this->input->post('role'))) {

			echo 'error-null';

		} else {

			// Get username all dept
			$this->db->where('username', $this->input->post('username'));
			$user = $this->db->get('users')->row();

			// is username duplicate
			if (! empty($user)) {

				echo 'error';

			} else {

				// insert users
				$data = array(	
								'dept_id' 		=> $this->input->post('dept'),
								'username' 		=> $this->input->post('username'),
								'password' 		=> md5(md5($this->input->post('password'))),
								'role_id' 		=> $this->input->post('role'),
								'created_time' 	=> date('Y-m-d H:i:s'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'creator' 		=> $this->session->userdata('user_id'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->insert('users', $data);

				// Log
				$this->log_activity_lib->activity_record('Users', 'Add', 'users', $this->db->insert_id(), $this->input->post('username'));

				echo 'success';

			}
			// End of if username duplicate

		}
		// End of if post null
		
	}
	// End of function save_new_user

	/**
	 * @param post => id
	 * @return bool
	 */
	public function delete_user() {
		
		// Permission
		$this->session_lib->check_permission('p_user_delete');

		$this->db->trans_start();

		$query = $this->db->query("SELECT username FROM users WHERE id = '" . $this->input->post('id') . "'")->row();

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('users');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			// Log
			$this->log_activity_lib->activity_record('Users', 'Delete', 'users', $this->input->post('id'), $query->username);

			$this->db->trans_commit();

			echo 'success';

		}

	}
	// End of function delete_user

}
/* End of file Users.php */
/* Location: ./application/controllers/Users.php/ */
