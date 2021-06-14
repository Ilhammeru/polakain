<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Vendor
 */
class Vendor extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_vendor_report');

		// Log
		$this->log_activity_lib->activity_record('Vendor', 'Visit');

		$this->layout_lib->default_template('master/vendor/report-data');
		
	}
	// End of function report_data

	public function form_new_vendor() {

		// Permission
		$this->session_lib->check_permission('p_vendor_add');

		// Log
		$this->log_activity_lib->activity_record('Form Vendor', 'Visit');

		$this->layout_lib->default_template('master/vendor/form-new-vendor');
		
	}
	// End of function form_new_vendor

	/**
	 * @param get => $id
	 */
	public function form_edit_vendor() {

		$id = $this->input->get('vendor_id');

		// Permission
		$this->session_lib->check_permission('p_vendor_edit');

		// Log
		$this->log_activity_lib->activity_record('Form Vendor', 'Visit');

		$attr = array(	
						'vendor_id' => $id,
						// Get vendor by id and dept
						'vendor' 	=> $this->db->query("SELECT vendor.id, 
																vendor.name, 
																vendor.address, 
																vendor.npwp, 
																vendor.updated_time, 
																username
															 	FROM vendor
															 	JOIN users ON users.id = vendor.updated_by 
															 	WHERE vendor.dept_id = '" . $this->session->userdata('dept_id') . "' 
															 	AND vendor.id = '$id'")->row_array()
					);

		$this->layout_lib->default_template('master/vendor/form-edit-vendor', $attr);
		
	}
	// End of function form_edit_vendor

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
		$columnOrder = array('name', 'address', 'npwp');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'vendor.name',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'vendor.address',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'vendor.npwp',
    							'type' 		=> 'search'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('vendor.name' => 'asc');

    	$countTotal = "SELECT id
    					 FROM vendor 
    					 WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

    	// Query
    	$query = "SELECT id,
    					 name,
    					 address,
    					 npwp
    					 FROM vendor ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'vendor');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$vendor = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($vendor)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->name;

			$row[] = $rows->address;

			$row[] = $rows->npwp;

			if ($this->session->userdata('p_vendor_edit') == 1) {

				$btnEdit = '<a href="' . site_url('vendor/form_edit_vendor?vendor_id=' . $rows->id) . '" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></a>';

			} else {

				$btnEdit = '';

			}

			if ($this->session->userdata('p_vendor_delete') == 1) {

				$btnDelete = '<a href="javascript:void(0)" id="btn-confirm-delete" key="' . $rows->id . '" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>';

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
	public function load_vendor_list() {

		if (empty($this->input->get('vendor'))) {

			$data['result'] = $this->db->query("SELECT id, name 
													   FROM vendor
													   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
													   ORDER BY name ASC")->result();

		} else {

			$data['result'] = $this->db->query("SELECT id, name 
													   FROM vendor
													   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
													   AND name LIKE '%" . $this->input->get('vendor') . "%'
													   ORDER BY name ASC")->result();

		}

		echo json_encode($data);
		
	}
	// End of function load_vendor_list

	/**
	 * @param $id
	 */
	public function load_modal_confirm_delete($id) {

		$attr = array('id' => $id);
		$this->load->view('master/vendor/modal-confirm-delete', $attr);
		
	}
	// End of function load_modal_confirm_delete

	/**
	 * @param post => vendor_name
	 * @param post => vendor_address
	 * @param post => vendor_npwp
	 */
	public function save_new_vendor() {

		// Permission
		$this->session_lib->check_permission('p_vendor_add');

		// is post null
		if (empty($this->input->post('vendor_name')) ||
			empty($this->input->post('vendor_address'))) {

		 	echo 'error-null';

		} else {

			// Get vendor by name and dept
			$condition = array('name' => ucwords($this->input->post('vendor_name')), 'dept_id' => $this->session->userdata('dept_id'));
			$this->db->where($condition);
			$vendor = $this->db->get('vendor')->row();

			// is vendor duplicate
			if (! empty($vendor)) {

				echo 'error';

			} else {

				// insert vendor
				$data = array(
								'dept_id' 		=> $this->session->userdata('dept_id'),
								'name'			=> ucwords($this->input->post('vendor_name')),
								'address' 		=> ucwords($this->input->post('vendor_address')),
								'npwp' 			=> ucwords($this->input->post('vendor_npwp')),
								'created_time' 	=> date('Y-m-d H:i:s'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'creator' 		=> $this->session->userdata('user_id'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->insert('vendor', $data);

				// Log
				$this->log_activity_lib->activity_record('Vendor', 'Add', 'vendor', $this->db->insert_id(), $this->input->post('vendor_name'));

				echo 'success';

			}
			// End of if vendor duplicate

		}
		// End of if post null
		
	}
	// End of function save_new_vendor

	/**
	 * @param $id
	 * @param post => vendor_name
	 * @param post => vendor_address
	 * @param post => vendor_npwp
	 */
	public function update_vendor($id) {

		// Permission
		$this->session_lib->check_permission('p_vendor_edit');

		// is post null
		if (empty($this->input->post('vendor_name'))) {

			echo 'error-null';

		} else {

			// Get vendor by name and ! vendor_id and dept_id
			$checkDuplicate = $this->db->query("SELECT name
									 				   FROM vendor
									 				   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
									 				   AND name = '" . ucwords($this->input->post('vendor_name')) . "'
									 				   AND id != '$id'")->num_rows();

			// is vendor duplicate
			if ($checkDuplicate > 0) {

				echo 'error';

			} else {

				// update vendor
				$data = array(
								'name' 			=> ucwords($this->input->post('vendor_name')),
								'address' 		=> ucwords($this->input->post('vendor_address')),
								'npwp' 			=> ucwords($this->input->post('vendor_npwp')),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->where('id', $id);
				$this->db->update('vendor', $data);

				// Log
				$this->log_activity_lib->activity_record('Vendor', 'Edit', 'vendor', $id, $this->input->post('vendor_name'));

				echo 'success';

			}
			// End of if vendor duplicate

		}
		// End of if post null
		
	}
	// End of function update_vendor

	/**
	 * @param post => id
	 * @return bool
	 */
	public function delete_vendor() {
		
		// Permission
		$this->session_lib->check_permission('p_vendor_delete');

		$this->db->trans_start();

		$query = $this->db->query("SELECT name 
										  FROM vendor 
										  WHERE id = '" . $this->input->post('id') . "'")->row();

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('vendor');

		// is successful trans
		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			// Log
			$this->log_activity_lib->activity_record('Vendor', 'Delete', 'item', $this->input->post('id'), $query->name);

			$this->db->trans_commit();

			echo 'success';

		}

	}
	// End of function delete_vendor

}
/* End of file Vendor.php */
/* Location: ./application/controllers/Vendor.php */
