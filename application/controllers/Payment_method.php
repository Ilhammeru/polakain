<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Payment_method
 * Payment_method as 'Metode Pembayaran'
 */
class Payment_method extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_payment_method_report');

		// Log
		$this->log_activity_lib->activity_record('Metode Pembayaran', 'Visit');

		$this->layout_lib->default_template('master/payment_method/report-data');
		
	}
	// End of function report_data

	public function form_new_payment_method() {

		// Permission
		$this->session_lib->check_permission('p_payment_method_add');

		// Log
		$this->log_activity_lib->activity_record('Form Metode Pembayaran', 'Visit');

		$type = array(
						array(
							'id' 	=> 1,
							'type' 	=> 'Rekening Pemasukan'
						),
						array(
							'id' 	=> 2,
							'type' 	=> 'Rekening Operasional'
						),
						array(
							'id' 	=> 3,
							'type' 	=> 'Rekening Pemasaran'
						),
						array(
							'id' 	=> 4,
							'type' 	=> 'Rekening Pembayaran'
						),
						array(
							'id' 	=> 5,
							'type' 	=> 'Mandiri Payroll'
						),
						array(
							'id'	=> 6,
							'type'	=> 'All'
						)
					);

		$attr = array(
						'type' 		=> $type,
						'bank_name' => $this->db->query("SELECT DISTINCT bank_name
																FROM payment_method
																WHERE dept_id = " . $this->session->userdata('dept_id') . "
																AND (bank_name IS NOT NULL 
																OR bank_name != '')")->result()
					);

		$this->layout_lib->default_template('master/payment_method/form-new-payment-method', $attr);
		
	}
	// End of function form_new_payment_method

	/**
	 * @param get => $id
	 */
	public function form_edit_payment_method() {

		$id = $this->input->get('payment_method_id');

		// Permission
		$this->session_lib->check_permission('p_payment_method_edit');

		// Log
		$this->log_activity_lib->activity_record('Form Metode Pembayaran', 'Visit');

		$type = array(
						array(
							'id' 	=> 1,
							'type' 	=> 'Rekening Pemasukan'
						),
						array(
							'id' 	=> 2,
							'type' 	=> 'Rekening Operasional'
						),
						array(
							'id' 	=> 3,
							'type' 	=> 'Rekening Pemasaran'
						),
						array(
							'id' 	=> 4,
							'type' 	=> 'Rekening Pembayaran'
						),
						array(
							'id' 	=> 5,
							'type' 	=> 'Mandiri Payroll'
						),
						array(
							'id'	=> 6,
							'type'	=> 'All'
						)
					);

		$status = array(
						array(
							'id' 		=> 1,
							'status' 	=> 'Aktif'
						),
						array(
							'id'		=> 0,
							'status'	=> 'Tidak Aktif'
						)
					);

		$attr = array(	
						'type' 				=> $type,
						'status'			=> $status,
						'payment_method_id' => $id,
						'bank_name' => $this->db->query("SELECT DISTINCT bank_name
																FROM payment_method
																WHERE dept_id = " . $this->session->userdata('dept_id') . "
																AND (bank_name IS NOT NULL 
																OR bank_name != '')")->result(),
						// Get payment_method by id and dept
						'payment_method' 	=> $this->db->query("SELECT payment_method.id, 
																		CASE 
																		WHEN type = 1 THEN 'Rekening Pemasukan'
																		WHEN type = 2 THEN 'Rekening Operasional'
																		WHEN type = 3 THEN 'Rekening Pemasaran'
																		WHEN type = 4 THEN 'Rekening Pembayaran'
																		WHEN type = 5 THEN 'Mandiri Payroll'
																		WHEN type = 6 THEN 'All'
																		END AS 'type',
																	 	payment_method.name,
																	 	payment_method.bank_name, 
																	 	payment_method.bank_number,
																	 	CASE
																	 	WHEN status =  1 THEN 'Aktif'
																	 	ELSE 'Tidak Aktif'
																	 	END AS 'status',
																	 	payment_method.updated_time, 
																	 	username
																	 	FROM payment_method
																	 	JOIN users ON users.id = payment_method.updated_by 
																	 	WHERE payment_method.dept_id = '" . $this->session->userdata('dept_id') . "' 
																	 	AND payment_method.id = '$id'")->row_array()
					);

		$this->layout_lib->default_template('master/payment_method/form-edit-payment-method', $attr);
		
	}
	// End of function form_edit_payment_method

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
		$columnOrder = array('type', 'name', 'bank_name', 'bank_number', 'status', '');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'payment_method.type',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'payment_method.name',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'payment_method.bank_name',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'payment_method.bank_number',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'payment_method.status',
    							'type' 		=> 'search'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('payment_method.name' => 'asc');

    	$countTotal = "SELECT id
    						  FROM payment_method
    						  WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

    	// Query
    	$query = "SELECT id,
    					 CASE 
						 WHEN type = 1 THEN 'Rekening Pemasukan'
						 WHEN type = 2 THEN 'Rekening Operasional'
						 WHEN type = 3 THEN 'Rekening Pemasaran'
						 WHEN type = 4 THEN 'Rekening Pembayaran'
						 WHEN type = 5 THEN 'Mandiri Payroll'
						 WHEN type = 6 THEN 'All'
						 END AS 'type',
    					 name,
    					 bank_name,
    					 bank_number,
    					 status
    					 FROM payment_method ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'payment_method');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$item = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($item)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->type;

			$row[] = $rows->name;

			$row[] = $rows->bank_name;

			$row[] = $rows->bank_number;

			if ($rows->status == 1) {
				$status = '<div class="badge badge-info">Aktif</div>';
			} else {
				$status = '<div class="badge badge-secondary">Tidak Aktif</div>';
			}

			$row[] = $status;

			if ($this->session->userdata('p_payment_method_edit') == 1) {

				$btnEdit = '<a href="' . site_url('payment_method/form_edit_payment_method?payment_method_id=' . $rows->id) . '" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></a>';
			} else {

				$btnEdit = '';

			}

			if ($this->session->userdata('p_payment_method_delete') == 1) {

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

	public function load_modal_confirm_delete($id) {

		$attr = array('id' => $id);
		$this->load->view('master/payment_method/modal-confirm-delete', $attr);
		
	}
	// End of function load_modal_confirm_delete

	/**
	 * @return json
	 */
	public function load_payment_method_list() {

		$data['result'] = $this->db->query("SELECT id, name
												   FROM payment_method 
												   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
												   ORDER BY name ASC")->result();

		echo json_encode($data);
		
	}
	// End of function load_payment_method_list

	/**
	 * @param post => type
	 * @param post => name
	 * @param post => bank_name
	 * @param post => bank_number
	 */
	public function save_new_payment_method() {

		// Permission
		$this->session_lib->check_permission('p_payment_method_add');

		// is post null
		if (empty($this->input->post('name'))) {

			echo 'error-null';

		} else {

			// Get payment_method by name and dept
			$condition = array('name' => ucwords($this->input->post('name')), 'dept_id' => $this->session->userdata('dept_id'));
			$this->db->where($condition);
			$paymentMethod = $this->db->get('payment_method')->row();

			// is name duplicate
			if (! empty($paymentMethod)) {

				echo 'error';

			} else {

				// insert payment_method
				$data = array(
								'dept_id' 		=> $this->session->userdata('dept_id'),
								'type' 			=> $this->input->post('type'),
								'name' 			=> ucwords($this->input->post('name')),
								'bank_name'		=> ucwords($this->input->post('bank_name')),
								'bank_number' 	=> ucwords($this->input->post('bank_number')),
								'status'		=> 1,
								'created_time' 	=> date('Y-m-d H:i:s'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'creator' 		=> $this->session->userdata('user_id'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->insert('payment_method', $data);

				// Log
				$this->log_activity_lib->activity_record('Metode Pembayaran', 'Add', 'payment_method', $this->db->insert_id(), $this->input->post('name'));

				echo 'success';

			}
			// End of if name duplicate

		}
		// End of if post null
		
	}
	// End of function save_new_payment_method

	/**
	 * @param $id
	 * @param post => type
	 * @param post => name
	 * @param post => bank_name
	 * @param post => bank_number
	 */
	public function update_payment_method($id) {

		// Permission
		$this->session_lib->check_permission('p_payment_method_edit');

		// is post null
		if (empty($this->input->post('name'))) {

			echo 'error-null';

		} else {

			// Get payment_method by name and ! payment_method_id and dept_id
			$checkDuplicate = $this->db->query("SELECT name
									 				   FROM payment_method
									 				   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
									 				   AND name = '" . ucwords($this->input->post('name')) . "'
									 				   AND id != '$id'")->num_rows();

			// is name duplicate
			if ($checkDuplicate > 0) {

				echo 'error';

			} else {

				// update payment_method
				$data = array(
								'type'			=> $this->input->post('type'),
								'name'			=> ucwords($this->input->post('name')),
								'bank_name' 	=> ucwords($this->input->post('bank_name')),
								'bank_number' 	=> ucwords($this->input->post('bank_number')),
								'status'		=> $this->input->post('status'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);

				$this->db->where('id', $id);
				$this->db->update('payment_method', $data);

				// Log
				$this->log_activity_lib->activity_record('Metode Pembayaran', 'Edit', 'payment_method', $id, $this->input->post('name'));

				echo 'success';

			}
			// End of if name duplicate

		}
		// End of if post null
		
	}
	// End of function update_payment_method

	/**
	 * @param post => id
	 * @return bool
	 */
	public function delete_payment_method() {
		
		// Permission
		$this->session_lib->check_permission('p_payment_method_delete');

		$this->db->trans_start();

		$query = $this->db->query("SELECT name FROM payment_method WHERE id = '" . $this->input->post('id') . "'")->row();

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('payment_method');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			// Log
			$this->log_activity_lib->activity_record('Metode Pembayaran', 'Delete', 'payment_method', $this->input->post('id'), $query->name);

			$this->db->trans_commit();

			echo 'success';

		}

	}
	// End of function delete_payment_method

}
/* End of file Payment_method.php */
/* Location: ./application/controllers/Payment_method.php */
