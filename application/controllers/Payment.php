<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Payment
 * Payment as 'Hutang Dagang'
 */
class Payment extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Payment');

	}
	// End of function __construct();

	public function index() {

		$attr = array(
						'filterVendor' => $this->display_filter_vendor(),
						'closingDate' => $this->closingDate
					);

		$this->layout_lib->default_template('transaction/payment/index', $attr);
		
	}
	// End of function index

	public function detail() {

		$result = $this->db->query("SELECT vendor.name AS 'vendor_name',
										   vendor.address AS 'vendor_address',
										   invoice.id AS 'invoice_id',
										   invoice_number,
										   date_invoice,
										   total_price,
										   ppn, 
										   d_cost,
										   disc,
										   IF(with_matoa_shipping = 0, total_price - disc + ppn + d_cost, total_price - disc + ppn) AS 'grand_total',
										   payment_dp.nominal AS 'dp_nominal',
										   payment_status,
										   payment.nominal,
										   payment.payment_date,
										   IF(payment.payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
										   CONCAT('-', payment_dp.nominal) AS 'payment_dp',
										   with_matoa_shipping
										   FROM invoice
										   JOIN vendor ON vendor.id = invoice.vendor_id
										   JOIN payment ON payment.invoice_id = invoice.id 
										   LEFT JOIN payment_dp ON payment_dp.invoice_id_used = invoice.id
										   LEFT JOIN payment_method ON payment_method.id = payment.payment_method_id AND payment.payment_method_id != 'z'
										   WHERE invoice.id = " . $this->input->get('invoice_id'))->row_array();

		$attr = array(
						'invoice' => $result
					);

		$this->layout_lib->default_template('transaction/payment/detail-invoice', $attr);
		
	}
	// End of function detail

	public function display_filter_vendor() {

		$vendor = $this->db->query("SELECT name 
										   FROM vendor 
										   WHERE dept_id = " . $this->session->userdata('dept_id') . "
										   ORDER BY name ASC")->result();

		$html = '<select class="form-control select2 select2-purple" multiple><option disabled>Filter Vendor</option>';

		foreach ($vendor as $row) :

			$html .= '<option>' . $row->name . '</option>';

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_vendor

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function server_side_data() {

		$results = $this->db->query("SELECT invoice.id,
					    					invoice_number,
					    					vendor.id AS 'vendor_id',
					    					vendor.name AS 'vendor_name',
					    					date_invoice,
					    					total_price,
					    					ppn,
					    					IF(with_matoa_shipping = 0, d_cost, 0) AS 'd_cost',
					    					IF(with_matoa_shipping = 1, d_cost, 0) AS 'titipan',
					    					disc,
					    					IF(with_matoa_shipping = 0, total_price + ppn + d_cost - disc, total_price + ppn - disc) AS 'grand_total'
					    					FROM invoice
					    					JOIN vendor ON vendor.id = invoice.vendor_id
					    					WHERE invoice.dept_id = " . $this->session->userdata('dept_id') . "
					    					AND payment_status = 3")->result();

		$payment_dp = $this->db->query("SELECT id,
											   nominal,
											   vendor_id,
											   payment_method_id
											   FROM payment_dp
											   WHERE dept_id = " . $this->session->userdata('dept_id') . "
											   AND payment_dp_used IS NULL
											   AND date_cancel IS NULL")->result();

		$payment_method = $this->db->query("SELECT id,
												   CONCAT(name, ' - ', bank_name) AS 'method'
												   FROM payment_method
												   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   AND status = 1")->result();


		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = date('d.m.Y H:i', strtotime($rows->date_invoice));

			$row[] = $rows->invoice_number;

			$row[] = $rows->vendor_name;

			$row[] = '<div class="text-right">' . number_format($rows->total_price, 2, '.', ',') . '</div>';

			$row[] = '<div class="text-right">' . number_format($rows->disc, 2, '.', ',') . '</div>';

			$row[] = '<div class="text-right">' . number_format($rows->ppn, 2, '.', ',') . '</div>';

			$row[] = '<div class="text-right">' . number_format($rows->d_cost, 2, '.', ',') . '</div>';

			$row[] = '<div class="text-right" id="grand_total_' . $rows->id . '">' . number_format($rows->grand_total, 2, '.', ',') . '</div><input type="hidden" id="input_grand_total_' . $rows->id . '" value="' . $rows->grand_total . '">';

			$row[] = '<div class="text-right">' . number_format($rows->titipan, 2, '.', ',') . '</div>';

			// $x = '<select id="payment_dp_' . $rows->id . '" class="form-control" onchange="update_nominal(' . $rows->id . ')">';
			// $x .= '<option value="" selected disabled data-nominal="0">Pilih DP</option>';

			// foreach ($payment_dp as $list) :

			// 	if ($list->vendor_id == $rows->vendor_id) {
			// 		$x .= '<option value="' . $list->id . '" data-nominal="' . $list->nominal . '" data-method="' . $list->payment_method_id . '">' . number_format($list->nominal, 2, ',', '.') . '</option>';
			// 	}

			// endforeach;

			// $x .= '</select>';

			$x = '';
			foreach ($payment_dp as $list) :

				if ($list->vendor_id == $rows->vendor_id) {
					$x .= '<p class="m-0"><input type="checkbox" class="payment_dp_' . $rows->id . '" id="payment_dp_' . $list->id . '" onclick="update_nominal(' . $rows->id . ')" data-nominal="' . $list->nominal . '" data-id="' . $list->id . '" data-method="' . $list->payment_method_id . '"> ' . number_format($list->nominal, 2, ',', '.') . '</p>';
				}

			endforeach;

			$row[] = $x;

			$y = '<select id="payment_method_' . $rows->id . '" class="form-control">';
			$y .= '<option value="" selected disabled>Pilih Pembayaran</option>';
			foreach ($payment_method as $list) :

				$y .= '<option value="' . $list->id . '">' . $list->method . '</option>';

			endforeach;

			$y .= '<option value="z">Tunai</option>';
			$y .= '</select>';

			$row[] = $y;

			if ($this->session->userdata('p_payment_approval') == 1) {

				$row[] = '<div class="text-center"><input type="checkbox" id="approve_payment_' . $rows->id . '" onclick="approve_checked(this)" class="approve_payment" value="' . $rows->id . '"></div>';

			} else {
				$row[] = '';
			}

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);

	}
	// End of function server_side_data


	public function server_side_data_history() {

		// Set field order column
		$columnOrder = array('date_invoice', 'invoice_number', 'vendor.name', 'payment.nominal', 'payment_date');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'date_invoice',
    							'type' 		=> 'daterange'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'invoice_number',
    							'type' 		=> 'search'
    						),
    						array(
    							'format'	=> 'string',
    							'field'		=> 'vendor.name',
    							'type'		=> 'select-multiple'
    						),
    						array(
    							'format'	=> 'string',
    							'field'     => 'payment.nominal',
    							'type' 		=> 'search'
    						),
    						array(
    							'format'	=> 'string',
    							'field'		=> 'payment_date',
    							'type'		=> 'daterange'
    						)
	    				);	
    	
    	// Set field ordering
    	$order = array('payment_date' => 'desc');

		$countTotal = "SELECT id
							  FROM payment
							  WHERE dept_id = " . $this->session->userdata('dept_id');

		$countTotal = $this->db->query($countTotal)->num_rows();

		$query = "SELECT payment_date,
						 date_invoice,
						 invoice.invoice_number,
						 invoice_id,
						 nominal,
						 payment_method_id,
						 is_cash,
						 IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
						 vendor.name As 'vendor_name'
						 FROM payment
						 LEFT JOIN payment_method ON payment_method.id = payment.payment_method_id AND payment_method_id != 'z'
						 JOIN invoice ON invoice.id = payment.invoice_id
						 JOIN vendor ON vendor.id = invoice.vendor_id ";

		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'payment');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$payment = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($payment)->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = date_format(date_create($rows->date_invoice), 'd M Y H:i:s');

			$row[] = $rows->invoice_number;

			$row[] = $rows->vendor_name;

			$row[] = '<div class="text-right">' . number_format($rows->nominal, 2, '.', ',') . '</div>';

			$row[] = date_format(date_create($rows->payment_date), 'd M Y H:i:s');

			$row[] = $rows->payment_method;

			$btnView = '<a href="' . site_url('payment/detail') . '?invoice_id=' . $rows->invoice_id . '" class="btn btn-sm btn-outline-info" target="_blank"><i class="fa fa-eye"></i></a>';

			if ($rows->is_cash == 1) {
				$btnCancel = '';
			} else {

				if ($this->session->userdata('p_payment_cancel') == 1 AND date('H:i:s') < $this->closingDate) {
					$btnCancel = '<a href="javascript:void(0)" onclick="cancel_approve(' . $rows->invoice_id . ')" class="btn btn-sm btn-outline-danger">Cancel</a>';
				} else {
					$btnCancel = '';
				}
			}

			$row[] = '<div class="btn-group">' . $btnView . $btnCancel . '</div>';

			$data[] = $row;

		endforeach;

		$output = array(
						"draw" 				=> $_POST['draw'],
            			"recordsTotal" 		=> $countTotal,
            			"recordsFiltered" 	=> $this->db->query($query)->num_rows(),
						"data" 				=> $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_history

	public function approve() {

		// Permission
		$this->session_lib->check_permission('p_payment_approval');

		$invoice = $this->db->query("SELECT IF(with_matoa_shipping = 0, total_price - disc + ppn + d_cost, total_price - disc + ppn) AS 'grand_total'
											FROM invoice
											WHERE id = " . $this->input->post('id'))->row_array();

		$payment_dp_id = $this->input->post('payment_dp_id');

		$data = array(
						'dept_id'			=> $this->session->userdata('dept_id'),
						'invoice_id'		=> $this->input->post('id'),
						'payment_date'		=> date('Y-m-d H:i:s'),
						'payment_method_id' => $this->input->post('payment_method_id'),
						'nominal'			=> $invoice['grand_total'],
						'created_time'		=> date('Y-m-d H:i:s'),
						'updated_time'		=> date('Y-m-d H:i:s'),
						'creator'			=> $this->session->userdata('user_id'),
						'updated_by'		=> $this->session->userdata('user_id')		
					);

		$this->db->trans_start();

		$this->db->insert('payment', $data);

		// Update payment_dp if used
		if ($payment_dp_id != '0') {

			$payment_dp_id = explode('-', $payment_dp_id);

			for ($i = 0; $i < count($payment_dp_id); $i++) {

				if ($i != 0) {
					$note = 'Telah digunakan pada tanggal ' . date('d M Y H:i:s');
					$this->db->query("UPDATE payment_dp SET payment_dp_used = '" . date('Y-m-d H:i:s') . "',
															invoice_id_used = " . $this->input->post('id') . ",
															note = '" . $note . "'
															WHERE id = " . $payment_dp_id[$i]);
				}

			}

		}

		#Payment Status => #1: DP, #2: LUNAS, #3: HUTANG
		// Update invoice
		$this->db->query("UPDATE invoice SET payment_status = 2 WHERE id = " . $this->input->post('id'));

		// Log

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function approve

	public function cancel_approve() {

		// Permission
		$this->session_lib->check_permission('p_payment_cancel');

		if (empty($this->input->post('invoice_id'))) {

			echo 'error-null';

		} else {

			$this->db->trans_start();

			#Payment Status => #1: DP, #2: LUNAS, #3: HUTANG
			// Update invoice
			$this->db->query("UPDATE invoice SET payment_status = 3 WHERE id = " . $this->input->post('invoice_id'));

			// Update payment_dp
			$this->db->query("UPDATE payment_dp SET payment_dp_used = NULL,
													invoice_id_used = NULL,
													note = NULL
													WHERE invoice_id_used = " . $this->input->post('invoice_id'));

			// Delete payment
			$this->db->where('invoice_id', $this->input->post('invoice_id'));
			$this->db->delete('payment');

			// Log

			if ($this->db->trans_status() === false) {

				$this->db->trans_rollback();

				echo 'error';

			} else {

				$this->db->trans_commit();

				echo 'success';

			}

		}
		
	}
	// End of function cancel_approve
	
}

/* End of file Payment.php */
/* Location: ./application/controllers/Payment.php */

