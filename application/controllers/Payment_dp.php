<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Payment_dp
 * Payment_dp as 'Pembelian Dimuka'
 */
class Payment_dp extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Payment DP');
	}
	// End of function __construct();

	public function input_data() {

		// Permission
		$this->session_lib->check_permission('p_payment_dp_report');

		// Log

		$attr = array(
						'payment_method' 	=> $this->db->query("SELECT id,
																		CONCAT(name, ' - ', bank_name) AS 'method'
																	 	FROM payment_method
																	 	WHERE dept_id = " . $this->session->userdata('dept_id') . "
																	 	AND status = 1
																	 	ORDER BY name, bank_name ASC")->result(),
						'vendor' 			=> $this->db->query("SELECT id,
															 			name
															 			FROM vendor
															 			WHERE dept_id = " . $this->session->userdata('dept_id') . "
															 			ORDER BY name ASC")->result(),
						'closingDate'		=> $this->closingDate
					);

		$this->layout_lib->default_template('transaction/payment_dp/input-data', $attr);

	}
	// End of function input_data

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function server_side_data() {

		$query = $this->db->query("SELECT payment_dp.id,
										  payment_date,
										  vendor.name AS 'vendor_name',
										  nominal,
										  note
										  FROM payment_dp
										  JOIN vendor ON vendor.id = payment_dp.vendor_id
										  WHERE payment_dp.dept_id = " . $this->session->userdata('dept_id') . "
										  AND payment_dp_used IS NULL
										  AND date_cancel IS NULL");

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = date('d.m.Y H:i', strtotime($rows->payment_date));

			//$row[] = date_format(date_create($rows->payment_date), 'd M Y H:i:s');

			$row[] = $rows->vendor_name;

			$row[] = '<div class="text-right">' . number_format($rows->nominal, 2, '.', ',') . '</div>';

			if ($this->session->userdata('p_payment_dp_delete') == 1 AND date('H:i:s') < $this->closingDate) {

				$btnDelete = '<a href="javascript:void(0)" onclick="delete_dp(' . $rows->id . ')"><i class="fa fa-trash text-red"></i></a>';

			} else {
				$btnDelete = '';
			}

			$row[] = '<button class="btn btn-xs btn-light btn-cancel" onclick="cancel_dp(' . $rows->id . ')"><i class="fa fa-times"></i></button>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data

	public function server_side_data_used() {

		$query = $this->db->query("SELECT payment_date,
										  vendor.name AS 'vendor_name',
										  nominal,
										  note,
										  payment_dp_used,
										  invoice_id_used,
										  invoice_number
										  FROM payment_dp
										  JOIN vendor ON vendor.id = payment_dp.vendor_id
										  JOIN invoice ON invoice.id = payment_dp.invoice_id_used
										  WHERE payment_dp.dept_id = " . $this->session->userdata('dept_id'));

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = date('d.m.Y H:i', strtotime($rows->payment_date));

			$row[] = $rows->invoice_number;

			$row[] = $rows->vendor_name;

			$row[] = $rows->note;

			$row[] = '<div class="text-right">' . number_format($rows->nominal, 2, '.', ',') . '</div>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_used

	public function server_side_data_cancel() {

		$query = $this->db->query("SELECT payment_dp.id,
										  payment_date,
										  vendor.name AS 'vendor_name',
										  nominal,
										  note,
										  date_cancel,
										  refund_method
										  FROM payment_dp
										  JOIN vendor ON vendor.id = payment_dp.vendor_id
										  WHERE payment_dp.dept_id = " . $this->session->userdata('dept_id') . "
										  AND date_cancel IS NOT NULL");

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = date('d.m.Y H:i', strtotime($rows->payment_date));

			$row[] = $rows->vendor_name;

			$row[] = '<div class="text-right">' . number_format($rows->nominal, 2, '.', ',') . '</div>';

			$row[] = date_format(date_create($rows->date_cancel), 'd M Y');

			$row[] = '<button class="btn btn-xs btn-light btn-delete-cancel" onclick="delete_cancel(' . $rows->id . ')"><i class="fa fa-times"></i></button>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_cancel

	/**
	 * @param post => vendor
	 * @param post => payment_method_id
	 * @param post => nominal
	 * @param post => note
	 */
	public function save_payment_dp() {

		// Permission
		$this->session_lib->check_permission('p_payment_dp_add');

		// If null

		$data = array(
						'dept_id' 			=> $this->session->userdata('dept_id'),
						'vendor_id' 		=> $this->input->post('vendor_id'),
						'payment_date' 		=> date('Y-m-d H:i:s'),
						'payment_method_id' => $this->input->post('payment_method_id'),
						'nominal' 			=> floatval(str_replace(",", "", $this->input->post('nominal'))),
						'note' 				=> $this->input->post('note'),
						'created_time' 		=> date('Y-m-d H:i:s'),
						'updated_time' 		=> date('Y-m-d H:i:s'),
						'creator'	 		=> $this->session->userdata('user_id'),
						'updated_by' 		=> $this->session->userdata('user_id')
					);

		$this->db->trans_start();

		$this->db->insert('payment_dp', $data);

		// Log

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function save_payment_dp

	public function delete_dp() {

		$id = $this->input->post('id');

		$this->db->trans_start();

		$this->db->where('id', $id);

		$this->db->delete('payment_dp');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function delete_dp

	/**
	 * @param post => id
	 * @param post => date_cancel
	 * @param post => payment_method
	 */
	public function cancel_dp() {

		$id = $this->input->post('id');
		$date_cancel = $this->input->post('date_cancel');
		$payment_method = $this->input->post('payment_method');

		$data = array(
						'date_cancel' => $date_cancel . ' ' . date('H:i:s'),
						'refund_method' => $payment_method
		);

		$this->db->where('id', $id);
		$this->db->update('payment_dp', $data);
	}
	// End of function cancel_dp

	/**
	 * @param post => id
	 */
	public function delete_cancel_dp() {

		$id = $this->input->post('id');

		$data = array(
						'date_cancel' => null,
						'refund_method' => null
		);

		$this->db->where('id', $id);
		$this->db->update('payment_dp', $data);
	}
	// End of function delete_cancel_dp

}
/* End of file Payment_dp.php */
/* Location: ./application/controllers/Payment_dp.php */
