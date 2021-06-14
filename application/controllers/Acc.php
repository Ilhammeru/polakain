<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
* Copyright (c) 2021 Sosial Lab
* @author
* @version
* @modify
* @updated
*/

class Acc extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Approval');

	}
	// End of function __construct();

	function approval() {

		// Permission
		$this->session_lib->check_permission('p_approval_report');

		$attr = array(
						'closingDate' => $this->closingDate,
						'filterStatus' => $this->filter_status(),
						'filterPaymentMethod' => $this->filter_payment_method()
					);

		$this->layout_lib->default_template('transaction/acc/approval', $attr);
	}
	// End of function approval

	public function history_approval() {

		$attr = array(
						'filterStatus' => $this->filter_status(),
						'filterPaymentMethod' => $this->filter_payment_method()
					);

		$this->layout_lib->default_template('transaction/acc/data-approval', $attr);
		
	}
	// End of function history_approval

	public function detail($id) {

		$query = "SELECT sale.id,
						 receipt_number,
						 customer_ref,
						 sale.created_time,
						 IF(sale.customer_id = 0, CONCAT('#Customer', sale.customer_ref), customer.name) AS 'customer_name',
						 date_dp,
						 DATEDIFF(due_date, NOW()) AS 'datediff',
						 ppn,
						 d_cost,
						 date_lunas,
						 date_piutang,
						 due_date,
						 date_kirim,
						 total_price,
						 nominal_bayar,
						 nominal_dp,
						 total_price + ppn + d_cost AS 'grand_total',
						 sale_status,
						 CASE 
						 WHEN sale_status = 1 THEN 'DP'
						 WHEN sale_status = 2 THEN 'Lunas'
						 WHEN sale_status = 3 THEN 'Piutang'
						 END AS 'sale_status_name',
						 CASE
						 WHEN sale_status = 1 THEN date_dp
						 WHEN sale_status = 2 THEN date_lunas
						 WHEN sale_status = 3 THEN due_date
						 END AS 'date_pay',
		 				 IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', payment_method.bank_name)) AS 'payment_method',
						 payment_method_id,
						 CASE
						 WHEN payment_method_dp_lunas IS NOT NULL THEN IF(sale.payment_method_dp_lunas = 'z', 'Tunai', IF(sale.payment_method_dp_lunas != 0, CONCAT(dp_lunas.name, ' - ', dp_lunas.bank_name), NULL))
						 ELSE IF(sale.payment_method_id = 'z', 'Tunai', IF(sale.payment_method_id != 0, CONCAT(payment_method.name, ' - ', payment_method.bank_name), NULL))
						 END AS 'payment_method_name'
						 FROM sale
						 LEFT JOIN customer ON customer.id = sale.customer_id AND sale.customer_id != 0
						 LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
						 LEFT JOIN payment_method dp_lunas ON dp_lunas.id = sale.payment_method_dp_lunas AND sale.payment_method_dp_lunas IS NOT NULL
						 WHERE sale.id = " . $id;


		$result = $this->db->query($query)->row_array();

		$attr = array(
						'receipt' => $result,
						'closingDate' => $this->closingDate
					);

		$this->layout_lib->default_template('transaction/acc/detail', $attr);
		
	}
	// End of function detail

	public function timeline($id) {

		$query = "SELECT sale.id,
						 receipt_number,
						 customer_ref,
						 sale.created_time,
						 date_dp,
						 date_lunas,
						 date_piutang,
						 due_date,
						 total_price,
						 nominal_bayar,
						 total_price + ppn + d_cost AS 'grand_total',
						 sale_status,
						 CASE 
						 WHEN sale_status = 1 THEN 'DP'
						 WHEN sale_status = 2 THEN 'Lunas'
						 WHEN sale_status = 3 THEN 'Piutang'
						 END AS 'sale_status_name',
		 				 IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
						 payment_method_id,
						 sale_record.detail
						 FROM sale
						 LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
						 LEFT JOIN sale_record ON sale_record.sale_id = sale.id 
						 WHERE sale.id = " . $id;

		$data = $this->db->query($query)->row_array();

		$attr = array(
						'data' => $data
					);

		$this->layout_lib->default_template('transaction/acc/timeline', $attr);
		
	}
	// End of function timeline

	public function filter_status() {

		$html = '<select class="form-control select2" multiple><option value="" disabled>Cari Status Pembayaran</option>';
		$html .= '<option value="1">DP</option>';
		$html .= '<option value="2">Lunas</option>';
		$html .= '<option value="3">Piutang</option>';
		$html .= '</select>';

		return $html;
		
	}
	// End of function filter_status

	public function filter_payment_method() {

		$result = $this->db->query("SELECT id,
								           CONCAT(name, ' - ', bank_name) AS 'method'
								           FROM payment_method
								           WHERE dept_id = " . $this->session->userdata('dept_id') . "
								           AND status = 1
								           ORDER BY name ASC")->result();

		$html = '<select class="form-control select2" multiple><option value="" disabled>Cari Metode Pembayaran</option>';

		foreach ($result as $list) :

			$html .= '<option value="' . $list->id . '">' . $list->method . '</option>';

		endforeach;

		$html .= '<option value="z">Tunai</option>';

		$html .= '</select>';

		return $html; 
		
	}
	// End of function filter_payment_method

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function server_side_data_approval() {

		$query = $this->db->query("SELECT sale.id,
										  receipt_number,
										  sale.created_time,
										  nominal_bayar,
										  sale_status,
										  CASE 
										  WHEN sale_status = 1 THEN 'DP'
										  WHEN sale_status = 2 THEN 'Lunas'
										  WHEN sale_status = 3 THEN 'Piutang'
										  END AS 'sale_status_name',
						 				  IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
										  payment_method_id
										  FROM sale
										  LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
										  WHERE sale.dept_id = " . $this->session->userdata('dept_id') . "
										  AND (date_lunas IS NULL
										  AND date_dp IS NULL
										  AND due_date IS NULL)
										  ORDER BY sale.created_time DESC");

		$payment_method = $this->db->query("SELECT id,
												   CONCAT(name, ' - ', bank_name) AS 'method'
												   FROM payment_method
												   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   AND status = 1")->result();

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->receipt_number;

			$row[] = date('d.m.Y H:i', strtotime($rows->created_time));

			if ($rows->sale_status == 1) {

			$row[] = '<input type="text" id="nominal_bayar_' . $rows->id . '" class="form-control currency-rp" value="' . $rows->nominal_bayar . '">';

			} else {

			$row[] = '' . number_format($rows->nominal_bayar, 2, ',', '.') . '<input type="hidden" id="nominal_bayar_' . $rows->id . '" value="' . $rows->nominal_bayar . '">';

			}

			$row[] = $rows->payment_method;

			if ($rows->sale_status == 1) {
				$sale_status_name = '<div class="badge badge-warning">' . $rows->sale_status_name . '</div>';
			} elseif ($rows->sale_status == 2) {
				$sale_status_name = '<div class="badge badge-success">' . $rows->sale_status_name . '</div>';
			} elseif ($rows->sale_status == 3) {
				$sale_status_name = '<div class="badge badge-danger">' . $rows->sale_status_name . '</div>';
			}

			$row[] = $sale_status_name . '<input type="hidden" id="sale_status_' . $rows->id . '" value="' . $rows->sale_status . '">';

			if ($rows->sale_status == 3) {

				$y = '';

			} else {

			$y = '<select id="payment_method_' . $rows->id . '" class="form-control">';
			$y .= '<option value="" selected disabled>Pilih Metode Pembayaran</option>';
			foreach ($payment_method as $list) :

				$y .= '<option value="' . $list->id . '">' . $list->method . '</option>';

			endforeach;

			$y .= '<option value="z">Tunai</option>';
			$y .= '</select>';

			}

			$row[] = $y;

			if ($rows->sale_status == 3) {
				$z = '<input type="text" id="due_date_' . $rows->id . '" class="form-control single-datepicker" readonly>';
			} else {
				$z = '';
			}

			$row[] = $z;

			if ($this->session->userdata('p_approval_submit') == 1) {

				$checkbox = '<div class="text-center"><input type="checkbox" id="approve_sales_' . $rows->id . '" onclick="approve_checked(this)" class="approve_sales" value="' . $rows->id . '"></div>';

			} else {
				$checkbox = '';
			}

			$row[] = $checkbox;

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_approval

	public function submit_approval() {

		// Permission
		$this->session_lib->check_permission('p_approval_submit');

		$this->db->trans_start();

		$sale_status = $this->input->post('sale_status');

		if ($sale_status == 3) {

			$data = array(
							'due_date' 			=> date_format(date_create($this->input->post('due_date')), 'Y-m-d H:i:s'),
							'updated_time'		=> date('Y-m-d H:i:s'),
							'updated_by' 		=> $this->session->userdata('user_id')
						);

		} else {

			$pay = floatval(
								str_replace(",", "", 
									str_replace(".00", "", $this->input->post('nominal_bayar'))
								)
							);

			$nominal_dp = 0;
			if ($sale_status == 1) {
				$date_dp 	= date('Y-m-d H:i:s');
				$date_lunas = null;
				$nominal_dp = $pay;
			} elseif ($sale_status == 2) {
				$date_dp 	= null;
				$date_lunas = date('Y-m-d H:i:s');
			}

			$data = array(
							'date_dp'			=> $date_dp,
							'date_lunas'		=> $date_lunas,
							'nominal_bayar'		=> $pay,
							'nominal_dp'		=> $pay,
							'payment_method_id' => $this->input->post('payment_method_id'),
							'updated_time'		=> date('Y-m-d H:i:s'),
							'updated_by' 		=> $this->session->userdata('user_id')
						);

		}

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('sale', $data);

		$this->db->query("UPDATE sale_record SET detail = JSON_SET(detail, '$." . date('Y-m-d H:i:s') . "', 
														JSON_OBJECT('status_menu', 1, 'act', 1, 'user_id', '" . $this->session->userdata('user_id') . "'))
									 			WHERE sale_id = " . $this->input->post('id'));

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function submit_approval

	public function server_side_data_dp() {

		$query = $this->db->query("SELECT sale.id,
										  receipt_number,
										  sale.created_time,
										  customer_ref,
										  total_price,
										  total_price + d_cost + ppn AS 'grand_total',
										  nominal_bayar,
										  total_price + d_cost + ppn - nominal_bayar AS 'pelunasan',
										  sale_status,
										  CASE 
										  WHEN sale_status = 1 THEN 'DP'
										  WHEN sale_status = 2 THEN 'Lunas'
										  WHEN sale_status = 3 THEN 'Piutang'
										  END AS 'sale_status_name',
						 				  IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
										  payment_method_id
										  FROM sale
										  LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
										  WHERE sale.dept_id = " . $this->session->userdata('dept_id') . "
										  AND (date_lunas IS NULL
										  AND date_dp IS NOT NULL)
										  ORDER BY sale.created_time DESC");

		$payment_method = $this->db->query("SELECT id,
												   CONCAT(name, ' - ', bank_name) AS 'method'
												   FROM payment_method
												   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   AND status = 1")->result();

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->receipt_number;

			$row[] = $rows->customer_ref;

			$row[] = date('d.m.Y H:i', strtotime($rows->created_time));

			$row[] = number_format($rows->grand_total, 2, '.', ',');

			$row[] = number_format($rows->nominal_bayar, 2, '.', ',');

			$row[] = number_format($rows->pelunasan, 2, '.', ',') . '<input type="hidden" id="nominal_bayar_' . $rows->id . '" value="' . $rows->grand_total . '">';

			$row[] = $rows->payment_method;

			$row[] = '<div class="badge badge-warning">' . $rows->sale_status_name . '</div>';

			$y = '<select id="dp_method_' . $rows->id . '" class="form-control">';
			$y .= '<option value="" selected disabled>Pilih Metode Pembayaran</option>';
			foreach ($payment_method as $list) :

				$y .= '<option value="' . $list->id . '">' . $list->method . '</option>';

			endforeach;

			$y .= '<option value="z">Tunai</option>';
			$y .= '</select>';

			$row[] = $y;

			if ($this->session->userdata('p_approval_submit') == 1) {

				$checkbox = '<div class="text-center"><input type="checkbox" id="approve_dp_' . $rows->id . '" onclick="approve_dp_checked(this)" class="approve_dp_sales" value="' . $rows->id . '"></div>';

			} else {
				$checkbox = '';
			}

			$row[] = $checkbox;

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_dp

	public function submit_dp() {

		// Permission
		$this->session_lib->check_permission('p_approval_submit');

		$this->db->trans_start();

		$data = array(
						'sale_status'				=> 2,
						'date_lunas'				=> date('Y-m-d H:i:s'),
						'nominal_bayar'				=> floatval($this->input->post('nominal_bayar')),
						'payment_method_dp_lunas' 	=> $this->input->post('dp_method_id'),
						'updated_time'				=> date('Y-m-d H:i:s'),
						'updated_by' 				=> $this->session->userdata('user_id')
					);

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('sale', $data);

		$this->db->query("UPDATE sale_record SET detail = JSON_SET(detail, '$." . date('Y-m-d H:i:s') . "', 
														JSON_OBJECT('status_menu', 2, 'act', 1, 'user_id', '" . $this->session->userdata('user_id') . "'))
									 			WHERE sale_id = " . $this->input->post('id'));

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function submit_dp

	public function server_side_data_piutang() {
		
		$query = $this->db->query("SELECT sale.id,
										  receipt_number,
										  customer_ref,
										  sale.created_time,
										  total_price,
										  total_price + d_cost + ppn  AS 'grand_total',
										  nominal_bayar,
										  total_price + d_cost + ppn - nominal_bayar AS 'pelunasan',
										  sale_status,
										  CASE 
										  WHEN sale_status = 1 THEN 'DP'
										  WHEN sale_status = 2 THEN 'Lunas'
										  WHEN sale_status = 3 THEN 'Piutang'
										  END AS 'sale_status_name',
						 				  IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
										  payment_method_id,
										  DATEDIFF(due_date, NOW()) AS 'datediff'
										  FROM sale
										  LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
										  WHERE sale.dept_id = " . $this->session->userdata('dept_id') . "
										  AND (date_lunas IS NULL
										  AND due_date IS NOT NULL)
										  ORDER BY sale.created_time DESC");

		$payment_method = $this->db->query("SELECT id,
												   CONCAT(name, ' - ', bank_name) AS 'method'
												   FROM payment_method
												   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   AND status = 1")->result();

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->receipt_number;

			$row[] = $rows->customer_ref;

			$row[] = date('d.m.Y H:i', strtotime($rows->created_time));

			$row[] = number_format($rows->grand_total, 2, '.', ',') . '<input type="hidden" id="nominal_bayar_' . $rows->id . '" value="' . $rows->grand_total . '">';

			if ($rows->datediff == 0) {
				$row[] = '<div class="badge badge-danger">Hari ini</div>';
			} elseif ($rows->datediff == 1) {
				$row[] = '<div class="badge badge-danger">Besok</div>';
			} elseif ($rows->datediff == 2) {
				$row[] = '<div class="badge badge-danger">Lusa</div>';
			} else {
				$row[] = $rows->datediff . ' hari lagi';
			}

			$row[] = '<div class="badge badge-danger">' . $rows->sale_status_name . '</div>';

			$y = '<select id="payment_method_' . $rows->id . '" class="form-control">';
			$y .= '<option value="" selected disabled>Pilih Metode Pembayaran</option>';
			foreach ($payment_method as $list) :

				$y .= '<option value="' . $list->id . '">' . $list->method . '</option>';

			endforeach;

			$y .= '<option value="z">Tunai</option>';
			$y .= '</select>';

			$row[] = $y;

			if ($this->session->userdata('p_approval_submit') == 1) {

				$checkbox = '<div class="text-center"><input type="checkbox" id="approve_piutang_' . $rows->id . '" onclick="approve_piutang_checked(this)" class="approve_piutang_sales" value="' . $rows->id . '"></div>';

			} else {
				$checkbox = '';
			}

			$row[] = $checkbox;

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);

	}
	// End of function server_side_data_piutang

	public function submit_piutang() {

		// Permission
		$this->session_lib->check_permission('p_approval_submit');

		$this->db->trans_start();

		$data = array(
						'sale_status'				=> 2,
						'date_lunas'				=> date('Y-m-d H:i:s'),
						'nominal_bayar'				=> floatval($this->input->post('nominal_bayar')),
						'payment_method_id' 		=> $this->input->post('payment_method_id'),
						'updated_time'				=> date('Y-m-d H:i:s'),
						'updated_by' 				=> $this->session->userdata('user_id')
					);

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('sale', $data);

		$this->db->query("UPDATE sale_record SET detail = JSON_SET(detail, '$." . date('Y-m-d H:i:s') . "', 
														JSON_OBJECT('status_menu', 3, 'act', 1, 'user_id', '" . $this->session->userdata('user_id') . "'))
									 			WHERE sale_id = " . $this->input->post('id'));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();

			echo 'error';
		} else {
			$this->db->trans_commit();

			echo 'success';
		}
	}
	// End of function submit_piutang

	public function server_side_data_history() {

		// Set field order column
		$columnOrder = array('receipt_number', 'customer_ref', 'sale.created_time', 'total_price', 'sale_status', 'payment_method_id');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'receipt_number',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'customer_ref',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'sale.created_time',
    							'type' 		=> 'daterange'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'total_price',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'sale_status',
    							'type' 		=> 'select-multiple'
    						),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'payment_method_id',
    							'type' 		=> 'select-multiple'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('item.name' => 'asc');

    	$countTotal = "SELECT id
    						  FROM sale
    						  WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

		$query = "SELECT sale.id,
						 receipt_number,
						 customer_ref,
						 sale.created_time,
						 date_dp,
						 date_lunas,
						 date_piutang,
						 due_date,
						 date_kirim,
						 total_price,
						 nominal_bayar,
						 total_price + ppn + d_cost AS 'grand_total',
						 sale_status,
						 CASE 
						 WHEN sale_status = 1 THEN 'DP'
						 WHEN sale_status = 2 THEN 'Lunas'
						 WHEN sale_status = 3 THEN 'Piutang'
						 END AS 'sale_status_name',
		 				 IF(payment_method_id = 'z', 'Tunai', CONCAT(payment_method.name, ' - ', bank_name)) AS 'payment_method',
						 payment_method_id
						 FROM sale
						 LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z' ";

		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'sale');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$sale = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($sale)->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->receipt_number;

			$row[] = $rows->customer_ref;

			$row[] = date_format(date_create($rows->created_time), 'd M Y H:i:s');

			$row[] = '<div class="text-right">' . number_format($rows->grand_total, 2, '.', ',') . '</div>';

			if ($rows->date_kirim != NULL) {

				$badge_kirim = '<div class="badge bg-purple">Dikirim</div>';

			} else {
				$badge_kirim = '';
			}

			if (($rows->sale_status == 1 && $rows->payment_method_id == 'z') || ($rows->sale_status == 1 && $rows->date_dp != NULL)) {

				$row[] = '<div class="badge badge-warning">DP</div> <div class="badge badge-primary">Approved</div>';

			} elseif ($rows->sale_status == 1 && $rows->date_dp == NULL) {

				$row[] = '<div class="badge badge-warning">DP</div> <div class="badge badge-secondary">Menunggu Approval</div>';

			} elseif ($rows->sale_status == 2 && $rows->date_lunas == NULL) {

				$row[] = '<div class="badge badge-success">Lunas</div> <div class="badge badge-secondary">Menunggu Approval</div>';

			} elseif ($rows->sale_status == 2 && $rows->date_lunas != NULL) {

				$row[] = '<div class="badge badge-success">Lunas</div> <div class="badge badge-primary">Approved</div> ' . $badge_kirim;

			} elseif ($rows->sale_status == 3 && $rows->due_date == NULL) {

				$row[] = '<div class="badge badge-danger">Piutang</div> <div class="badge badge-secondary">Menunggu Approval</div>';

			} elseif ($rows->sale_status == 3 && $rows->due_date != NULL) {

				$row[] = '<div class="badge badge-danger">Piutang</div> <div class="badge badge-primary">Approved</div> ' . $badge_kirim;

			}

			$row[] = $rows->payment_method;

			$row[] = '<a href="' . site_url('acc/detail/' . $rows->id) . '" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-eye"></i></a>';

			$data[] = $row;

		endforeach;

		$output = array(
						"draw" 				=> $_POST['draw'],
            			"recordsTotal" 		=> $countTotal,
            			"recordsFiltered" 	=> $this->db->query($query)->num_rows(),
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_history

	public function cancel_approved() {

		// Permission
		$this->session_lib->check_permission('p_approval_cancel');

		$id = $this->input->post('id');
		$key = $this->input->post('key');

		$this->db->trans_start();

		switch ($key) :

			case 'x' :

				$data = array(
								'date_lunas' => null,
								'updated_by' => $this->session->userdata('user_id'),
								'updated_time' => date('Y-m-d H:i:s')
							);

				break;

			case 'y' :

				$data = array(
								'date_dp' => null,
								'nominal_bayar' => 0,
								'updated_by' => $this->session->userdata('user_id'),
								'updated_time' => date('Y-m-d H:i:s')
							);

				break;

			case 'z' :

				$data = array(
								'due_date' => null,
								'updated_by' => $this->session->userdata('user_id'),
								'updated_time' => date('Y-m-d H:i:s')
							);

				break;

			case 'ly' :

				$data = array(
								'nominal_bayar' => floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('nominal_dp')))),
								'date_lunas' => null,
								'sale_status' => 1,
								'updated_by' => $this->session->userdata('user_id'),
								'updated_time' => date('Y-m-d H:i:s')
							);

				break;

			case 'lz' :

				$data = array(	
								'nominal_bayar'    => 0,
								'date_lunas' => null,
								'sale_status' => 3,
								'updated_by' => $this->session->userdata('user_id'),
								'updated_time' => date('Y-m-d H:i:s')
							);

				break;

		endswitch;

		$this->db->where('id', $id);
		$this->db->update('sale', $data);

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function cancel_approved

}
/* End of file Acc.php */
/* Location: ./application/controllers/Acc.php */

