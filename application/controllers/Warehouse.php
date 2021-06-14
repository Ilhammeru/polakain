<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Warehouse
 */
class Warehouse extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_warehouse_report');

		// Log
		$this->log_activity_lib->activity_record('Gudang', 'Visit');

		$this->layout_lib->default_template('master/warehouse/report-data');
		
	}
	// End of function report_data

	public function form_new_warehouse() {

		// Permission
		$this->session_lib->check_permission('p_warehouse_add');

		// Log
		$this->log_activity_lib->activity_record('Form Gudang', 'Visit');

		$this->layout_lib->default_template('master/warehouse/form-new-warehouse');
		
	}
	// End of function form_new_warehouse

	/**
	 * @param get => $id
	 */
	public function form_edit_warehouse() {

		$id = $this->input->get('warehouse_id');

		// Permission
		$this->session_lib->check_permission('p_warehouse_edit');

		// Log
		$this->log_activity_lib->activity_record('Form Gudang', 'Visit');

		$attr = array(	
						'warehouse_id' 	=> $id,
						// Get warehouse by id and dept
						'warehouse' 	=> $this->db->query("SELECT warehouse.id, warehouse.name, warehouse.updated_time, username
																	FROM warehouse 
																	JOIN users ON users.id = warehouse.updated_by 
																	WHERE warehouse.dept_id = '" . $this->session->userdata('dept_id') . "' 
																	AND warehouse.id = '$id'")->row_array()
					);

		$this->layout_lib->default_template('master/warehouse/form-edit-warehouse', $attr);
		
	}
	// End of function form_edit_warehouse

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
		$columnOrder = array('name', '');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'warehouse.name',
    							'type' 		=> 'search'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('warehouse.name' => 'asc');

    	$countTotal = "SELECT id
    						  FROM warehouse
    						  WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

    	// Query
    	$query = "SELECT id, name
    					 FROM warehouse ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'warehouse');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$warehouse = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($warehouse)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->name;

			if ($this->session->userdata('p_warehouse_edit') == 1) {

				$btnEdit = '<a href="' . site_url('warehouse/form_edit_warehouse?warehouse_id=' . $rows->id) . '" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></a>';

			} else {

				$btnEdit = '';

			}

			if ($this->session->userdata('p_warehouse_delete') == 1) {

				$btnDelete = '<a href="javascript:void(0)" id="btn-confirm-delete" key=' . $rows->id . ' class="btn btn-sm btn-outline-danger" ><i class="fa fa-trash"></i></a>';

			} else {


				$btnDelete = '';

			}

			$row[] = '<div class="btn-group">' . $btnEdit . $btnDelete . '</div>';

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

	/**
	 * @return json
	 */
	public function load_warehouse_list() {

		$data['result'] = $this->db->query("SELECT id, name 
												   FROM warehouse 
												   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
												   ORDER BY name ASC")->result();

		echo json_encode($data);
		
	}
	// End of function load_warehouse_list

	public function load_modal_confirm_delete($id) {

		$attr = array('id' => $id);
		$this->load->view('master/warehouse/modal-confirm-delete', $attr);
		
	}
	// End of function load_modal_confirm_delete

	/**
	 * @param post => warehouse_name
	 */
	public function save_new_warehouse() {

		// Permission
		$this->session_lib->check_permission('p_warehouse_add');

		// is post null
		if (empty($this->input->post('warehouse_name'))) {

			echo 'error-null';

		} else {

			// Get warehouse by name and dept_id
			$condition = array('name' => ucwords($this->input->post('warehouse_name')), 'dept_id' => $this->session->userdata('dept_id'));
			$this->db->where($condition);
			$warehouse = $this->db->get('warehouse')->row();

			// is warehouse duplicate
			if (! empty($warehouse)) {

				echo 'error';

			} else {

				// insert users
				$data = array(
								'dept_id' 		=> $this->session->userdata('dept_id'),
								'name' 			=> ucwords($this->input->post('warehouse_name')),
								'created_time' 	=> date('Y-m-d H:i:s'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'creator' 		=> $this->session->userdata('user_id'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->insert('warehouse', $data);

				// Log
				$this->log_activity_lib->activity_record('Gudang', 'Add', 'warehouse', $this->db->insert_id(), $this->input->post('warehouse_name'));

				echo 'success';

			}
			// End of if warehouse duplicate

		}
		// End of if post null
		
	}
	// End of function save_new_warehouse

	/**
	 * @param $id
	 * @param post => warehouse_name
	 */
	public function update_warehouse($id) {

		// Permission
		$this->session_lib->check_permission('p_warehouse_edit');

		// is post null
		if (empty($this->input->post('warehouse_name'))) {

			echo 'error-null';

		} else {

			// Get warehouse by name and ! warehouse_id and dept_id
			$checkDuplicate = $this->db->query("SELECT name
									 				   FROM warehouse
									 				   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
									 				   AND name = '" . ucwords($this->input->post('warehouse_name')) . "'
									 				   AND id != '$id'")->num_rows();

			// is warehouse duplicate
			if ($checkDuplicate > 0) {

				echo 'error';

			} else {

				// update warehouse
				$data = array(
								'name' 			=> ucwords($this->input->post('warehouse_name')),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->where('id', $id);
				$this->db->update('warehouse', $data);

				// Log
				$this->log_activity_lib->activity_record('Gudang', 'Edit', 'warehouse', $id, $this->input->post('warehouse_name'));

				echo 'success';

			}
			// End of if warehouse duplicate

		}
		// End of if post null
		
	}
	// End of function update_warehouse

	/**
	 * @param post => id
	 * @return bool
	 */
	public function delete_warehouse() {
		
		// Permission
		$this->session_lib->check_permission('p_warehouse_delete');

		$this->db->trans_start();

		$query = $this->db->query("SELECT name 
										  FROM warehouse 
										  WHERE id = '" . $this->input->post('id') . "'")->row();

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('warehouse');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			// Log
			$this->log_activity_lib->activity_record('Gudang', 'Delete', 'warehouse', $this->input->post('id'), $query->name);

			$this->db->trans_commit();

			echo 'success';

		}

	}
	// End of function delete_warehouse

}
/* End of file Warehouse.php */
/* Location: ./application/controllers/Warehouse.php */
