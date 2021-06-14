<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Invoice
 * Invoice as 'Invoice Barang Datang'
 */
class Invoice extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Invoice');

	}
	// End of function __construct();

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_invoice_report');

		$attr = array(
						'filterStatus' => $this->filter_status(),
						'filterVendor' => $this->display_filter_vendor(),
						'closingDate' => $this->closingDate
					);

		$this->layout_lib->default_template('transaction/invoice/report-data', $attr);
		
	}
	// End of function report_data

	public function form_new_barang_datang() {

		// Permission
		$this->session_lib->check_permission('p_invoice_add');

		// Log

		$attr = array(
						'vendor' 	=> $this->db->query("SELECT id, name 
																FROM vendor 
																WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																ORDER BY name ASC")->result(),
						'warehouse' => $this->db->query("SELECT id, 
																name 
																FROM warehouse 
																WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																ORDER BY name ASC")->result(),
						'item' 		=> $this->db->query("SELECT id,
																name, 
																code 
																FROM item 
																WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																ORDER BY name ASC")->result()
					);

		$this->layout_lib->default_template('transaction/invoice/form-new-invoice', $attr);
	}
	// End of function form_new_barang_datang

	/**
	 * @param get => invoice_id
	 */
	function detail() {

		$attr = array(
						'invoice' => $this->db->query("SELECT vendor.name AS 'vendor_name',
															  vendor.address AS 'vendor_address',
															  invoice.id AS 'invoice_id',
															  invoice_number,
															  date_invoice,
															  total_price,
															  ppn, 
															  d_cost,
															  disc,
															  IF(with_matoa_shipping = 0, total_price - disc + ppn + d_cost, total_price - disc + ppn) AS 'grand_total',
															  payment_status,
															  payment_date,
															  with_matoa_shipping
															  FROM invoice
															  JOIN vendor ON vendor.id = invoice.vendor_id
															  LEFT JOIN payment ON payment.invoice_id = invoice.id
															  WHERE invoice.id = " . $this->input->get('invoice_id'))->row_array()
					);

		$this->layout_lib->default_template('transaction/invoice/detail-invoice', $attr);

	}
	// End of function detail

	/**
	 * @param get => $invoice_id
	 */
	public function form_edit_barang_datang() {

		// Permission
		$this->session_lib->check_permission('p_invoice_edit');

		// Log

		$id = $this->input->get('invoice_id');

		$attr = array(
						'invoice' 		=> $this->db->query("SELECT invoice.id, 
															  vendor_id,
															  vendor.name AS 'vendor_name', 
															  date_invoice, 
															  invoice_number,
															  total_price,
															  ppn, 
															  d_cost,
															  disc,
															  with_matoa_shipping,
															  payment_status
															  FROM invoice 
															  JOIN vendor ON vendor.id = invoice.vendor_id
															  WHERE invoice.id = " . $id)->row_array(),
						'invoice_id' 	=> $id,
						'vendor' 		=> $this->db->query("SELECT id, 
																	name 
																	FROM vendor 
																	WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																	ORDER BY name ASC")->result(),
						'warehouse' 	=> $this->db->query("SELECT id, 
																	name 
																	FROM warehouse 
																	WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																	ORDER BY name ASC")->result(),
						'item' 			=> $this->db->query("SELECT id, 
																	name, 
																	code 
																	FROM item 
																	WHERE dept_id = " . $this->session->userdata('dept_id') . " 
																	ORDER BY name ASC")->result()
					);

		$this->layout_lib->default_template('transaction/invoice/form-edit-invoice', $attr);
		
	}
	// End of function form_edit_barang_datang

	public function filter_status() {

		$html = '<select class="form-control select2 select2-purple" multiple><option disabled>Cari Status</option>';
		$html .= '<option value="2">LUNAS</option>';
		$html .= '<option value="3">HUTANG</option>';
		$html .= '</select>';

		return $html;
		
	}
	// End of function filter_status

	public function display_filter_vendor() {

		$vendor = $this->db->query("SELECT name 
										   FROM vendor 
										   WHERE dept_id = " . $this->session->userdata('dept_id') . "
										   ORDER BY name ASC")->result();

		$html = '<select class="form-control select2 select2-purple" multiple><option disabled>Cari Vendor</option>';

		foreach ($vendor as $row) :

			$html .= '<option>' . $row->name . '</option>';

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_vendor

    /**
	 * @param post => code
	 */
	public function scan_barcode() {

		$code = $this->input->post('code');

		$query = $this->db->query("SELECT id, name, code
										FROM item
										WHERE code = '" . $code . "'
										AND dept_id = " . $this->session->userdata('dept_id'));
		
		if ($query->num_rows() > 0) {
			$query = $query->row_array();

			$explode = explode('.', $query['code']);
			$color_id = $explode[2];

			$colorData = $this->db->query("SELECT name
												FROM itemcolor
												WHERE id = " . $color_id)->row_array();

			$colorName = $colorData['name'];

			$data = array(
							'id'  => $query['id'],
							'code' => $query['code'],
							'color' => $colorName,
							'response' => 'success'
			);

			echo json_encode($data);
		} else {
			echo json_encode(array('response' => 'error-null'));
		}
	}
	// End of function scan_barcode

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
		// $columnOrder = array('invoice_number', 'vendor.name', 'date_invoice', 'total_price', 'disc', 'ppn', 'd_cost', 'grand_total', 'payment_status', '');

		$columnOrder = array('invoice_number', 'vendor.name', 'date_invoice', 'total_price', 'payment_status', '');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'invoice_number',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'vendor.name',
    							'type'		=> 'select-multiple'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'date_invoice',
    							'type' 		=> 'daterange'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'total_price',
    							'type' 		=> 'search'
    						),
    						// array(
    						// 	'format' 	=>  'string',
    						// 	'field' 	=> 'disc',
    						// 	'type' 		=> 'search'
    						// ),
    						// array(
    						// 	'format' 	=>  'string',
    						// 	'field' 	=> 'ppn',
    						// 	'type' 		=> 'search'
    						// ),
    						// array(
    						// 	'format' 	=>  'string',
    						// 	'field' 	=> 'd_cost',
    						// 	'type' 		=> 'search'
    						// ),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'payment_status',
    							'type' 		=> 'select-multiple'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('date_invoice' => 'desc');

    	$countTotal = "SELECT id
    						  FROM invoice
    						  WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

    	// Query
    	$query = "SELECT invoice.id,
    					 invoice_number,
    					 vendor.name AS 'vendor_name',
    					 date_invoice,
    					 total_price,
    					 ppn,
    					 d_cost,
    					 disc,
    					 total_price + ppn + d_cost - disc AS 'grand_total',
    					 payment_status
    					 FROM invoice
    					 JOIN vendor ON vendor.id = invoice.vendor_id ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'invoice');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$invoice = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($invoice)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->invoice_number;

			$row[] = $rows->vendor_name;

			$row[] = date_format(date_create($rows->date_invoice), 'd M Y H:i:s');

			$row[] = '<div class="text-right">' . number_format($rows->total_price, 2, '.', ',') . '</div>';

			// $row[] = '<div class="text-right">Rp ' . number_format($rows->disc, 2, '.', ',') . '</div>';

			// $row[] = '<div class="text-right">Rp ' . number_format($rows->ppn, 2, '.', ',') . '</div>';

			// $row[] = '<div class="text-right">Rp ' . number_format($rows->d_cost, 2, '.', ',') . '</div>';

			// $row[] = '<div class="text-right">Rp ' . number_format($rows->grand_total, 2, '.', ',') . '</div>';

			if ($rows->payment_status == 2) {
			$row[] = '<div class="badge badge-success">LUNAS</div>';
			} elseif ($rows->payment_status == 3) {
			$row[] = '<div class="badge badge-danger">HUTANG</div>';
			} else {
			$row[] = $rows->payment_status;
			}

			// Permission
			$btnView = '<a href="' . site_url('invoice/detail') . '?invoice_id=' . $rows->id . '" class="btn btn-sm btn-outline-info" target="_blank"><i class="fa fa-eye"></i></a>';

			if ($this->session->userdata('p_invoice_edit') == 1 AND $rows->payment_status != '2' AND date('H:i:s') < $this->closingDate) {

				// Permission
				$btnEdit = '<a href="' . site_url('invoice/form_edit_barang_datang') . '?invoice_id=' . $rows->id . '" class="btn btn-sm btn-outline-warning" target="_blank"><i class="fa fa-edit"></i></a>';

			} else {
				$btnEdit = '';
			}

			if ($this->session->userdata('p_invoice_delete') == 1 AND date('H:i:s') < $this->closingDate) {

				// Permission
				$btnDelete = '<a href="javascript:void(0)" id="btn-confirm-delete" key="' . $rows->id . '" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>';

			} else {
				$btnDelete = '';
			}

			$row[] = '<div class="btn-group">' . $btnView . $btnEdit . $btnDelete . '</div>';

			$data[] = $row;

		endforeach;

		// Results
		$output = array(
						"draw" => $_POST['draw'],
            			"recordsTotal" => $countTotal,
            			"recordsFiltered" => $this->db->query($query)->num_rows(),
						"data" => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data

	public function server_side_data_invoice() {

		$results = $this->db->query("SELECT id,
											name,
											code
											FROM item
											WHERE dept_id = " . $this->session->userdata('dept_id') . "
											ORDER BY name ASC")->result();

		$warehouse = $this->db->query("SELECT id,
											  name
											  FROM warehouse
											  WHERE dept_id = " . $this->session->userdata('dept_id'))->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->code . '<input type="hidden" name="item_id[]" value="' . $rows->id . '">';

			$row[] = $rows->name;

			foreach ($warehouse as $list) :
				$row[]  = '<input type="number" name="warehouse_' . $list->id . '[]" class="form-control warehouse_' . $list->id . '" step="1" min="0">';
			endforeach;

			$row[] = '<input type="text" name="price[]" class="form-control price currency-rp">';

			$row[] = '<input type="text" name="totalprice[]" class="form-control totalprice currency-rp" onkeyup="calculate()">';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);

	}
	// End of function server_side_data_invoice

	public function server_side_data_invoice_detail($invoice_id) {

		$results = $this->db->query("SELECT sum(qty) AS 'qty',
											item.name AS 'item_name',
											item.code AS 'item_code',
											price,
											total_price
											FROM invoice_detail
											JOIN item ON item.id = invoice_detail.item_id
											WHERE invoice_id = " . $invoice_id . "
											GROUP BY item.id")->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = '<div class="text-center">' . $rows->qty . '</div>';

			$row[] = $rows->item_name;

			$row[] = '<div class="text-center">' . $rows->item_code . '</div>';

			$row[] = '<div class="text-center">' . number_format($rows->price, '2', '.', ',') . '</div>';

			$row[] = '<div class="text-right">' . number_format($rows->total_price, '2', '.', ',') . '</div>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_invoice_detail

	/**
	 * @param get $invoice_id
	 */
	public function server_side_data_invoice_edit($invoice_id) {

		$warehouse = $this->db->query("SELECT id,
											  name
											  FROM warehouse
											  WHERE dept_id = " . $this->session->userdata('dept_id'))->result();

		$x = "";
		foreach ($warehouse as $list) :
			$x .= "SUM(IF(warehouse_id = " . $list->id . ", qty, 0)) AS 'warehouse_" . $list->id . "',";
		endforeach;

		$results = $this->db->query("SELECT item.id AS 'item_id', 
											item.name AS 'item_name',
											sum(qty) AS 'qty',
											" . $x . "
											IF(price IS NULL, 0.00, price) AS 'price',
											IF(total_price IS NULL, 0.00, total_price) AS 'total_price',
											d_cost,
											item.code
											FROM item
											LEFT JOIN invoice_detail ON invoice_detail.item_id = item.id AND invoice_id = " . $invoice_id . "
											AND dept_id = " . $this->session->userdata('dept_id') . "
											GROUP BY item.id
											ORDER BY item.name ASC")->result_array();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows['code'] . '<input type="hidden" name="item_id[]" value="' . $rows['item_id'] . '">';

			$row[] = $rows['item_name'];

			foreach ($warehouse as $list) :
				$row[]  = '<input type="number" name="warehouse_' . $list->id . '[]" class="form-control warehouse_' . $list->id . '" step="1" min="0" value="' . $rows['warehouse_' . $list->id] . '">';
			endforeach;

			$row[] = '<input type="text" name="price[]" class="form-control price currency-rp" value="' . $rows['price'] . '">';

			$row[] = '<input type="text" name="totalprice[]" class="form-control totalprice currency-rp" onkeyup="calculate()" value="' . $rows['total_price'] . '">';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);

	}
	// End of function server_side_data_invoice_edit

	/**
	 * @param $id
	 */
	public function load_modal_confirm_delete($id) {

		$attr = array('id' => $id);
		$this->load->view('transaction/invoice/modal-confirm-delete', $attr);
		
	}
	// End of function load_modal_confirm_delete

	/**
	 * @param post => invoice_number
	 * @param post => vendor_id
	 * @param post => ppn
	 * @param post => d_cost
	 */
	public function save_barang_datang() {

		// Permission
		$this->session_lib->check_permission('p_invoice_add');

		// If null

		#Payment Status => #1: DP, #2: LUNAS, #3: HUTANG

		$item 		= $this->input->post('item_id');
		$price 		= $this->input->post('price');
		$totalprice = $this->input->post('totalprice');
		$grandtotal = floatval(str_replace(",", "", $this->input->post('grandtotal')));
		$disc 		= floatval(str_replace(",", "", $this->input->post('disc')));
		$d_cost 	= floatval(str_replace(",", "", $this->input->post('d_cost')));
		$with_matoa_shipping = $this->input->post('with_matoa_shipping');
		$payment_status = $this->input->post('payment_status');

		$warehouse = $this->db->query("SELECT id,
		 									  name
		 									  FROM warehouse
		 									  WHERE dept_id = " . $this->session->userdata('dept_id'))->result();

		// Validation
		// for ($i = 0; $i < count($item); $i++) {

		// 	$y = array();
		// 	foreach ($warehouse as $list) :

		// 		$y['warehouse_' . $list->id] = $this->input->post('warehouse_' . $list->id);

		// 		if ($y['warehouse_' . $list->id][$i] != null) {

		// 			if ($price[$i] == null || $totalprice[$i] == null) {

		// 				echo 'error-null-1';

		// 				return false;

		// 			}

		// 		}

		// 	endforeach;

		// }

		$is_ppn = 0;
		if ($this->input->post('ppn') == '' || $this->input->post('ppn') == NULL || $this->input->post('ppn') == 0) {
			$is_ppn = 1;
		}

		$data = array(
						'dept_id' 			=> $this->session->userdata('dept_id'),
						'invoice_number' 	=> ucwords($this->input->post('invoice_number')),
						'vendor_id' 		=> $this->input->post('vendor_id'),
						'date_invoice' 		=> date('Y-m-d H:i:s'),
						'total_price' 		=> $grandtotal,
						'disc'	 			=> $disc,
						'ppn' 				=> floatval(str_replace(",", "", $this->input->post('ppn'))),
						'is_ppn' 			=> $is_ppn,
						'd_cost' 			=> $d_cost,
						'with_matoa_shipping' => $with_matoa_shipping,
						'payment_status' 	=> $payment_status,
						'created_time' 		=> date('Y-m-d H:i:s'),
						'updated_time' 		=> date('Y-m-d H:i:s'),
						'creator' 			=> $this->session->userdata('user_id'),
						'updated_by' 		=> $this->session->userdata('user_id')
					);

		$this->db->trans_start();

		$this->db->insert('invoice', $data);

		$insert_id = $this->db->insert_id();

		for ($i = 0; $i < count($item); $i++) {

			// $y = array();
			// foreach ($warehouse as $list) :

			// 	$y['warehouse_' . $list->id] = $this->input->post('warehouse_' . $list->id);

			// 	if ($y['warehouse_' . $list->id][$i] != null) {

					if ($price[$i] != null && $totalprice[$i] != null) {

						# Rumus: price/total price * 100
						$d_cost_item = 0;
						if ($d_cost != null) {
							$d_cost_item = round(floatval($totalprice[$i])/$grandtotal, 2, PHP_ROUND_HALF_DOWN) * $d_cost;
						}

						$data_detail[] = array(
												'invoice_id' 	=> $insert_id,
												'item_id'	 	=> $item[$i],
												'warehouse_id' 	=> $this->session->userdata('p_warehouse_id'),
												'qty'			=> intval($this->input->post('warehouse_qty')[$i]),
												'price' 		=> floatval(str_replace(",", "", $price[$i])),
												'total_price' 	=> floatval(str_replace(",", "", $totalprice[$i])),
												'd_cost' 		=> floatval(str_replace(",", "", $d_cost_item))
											);

					}

			// 	}

			// endforeach;

		}

		$this->db->insert_batch('invoice_detail', $data_detail);

		if ($payment_status == 2) {

			$dataPayment = array(
								'dept_id'			=> $this->session->userdata('dept_id'),
								'invoice_id'		=> $insert_id,
								'payment_date'		=> date('Y-m-d H:i:s'),
								'payment_method_id' => 'z',
								'nominal'			=> $grandtotal,
								'is_cash'			=> 1,
								'created_time'		=> date('Y-m-d H:i:s'),
								'updated_time'		=> date('Y-m-d H:i:s'),
								'creator'			=> $this->session->userdata('user_id'),
								'updated_by'		=> $this->session->userdata('user_id')		
							);

			$this->db->insert('payment', $dataPayment);

		}

		// Log

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function save_barang_datang

	/**
	 * @param post => invoice_number
	 * @param post => vendor_id
	 * @param post => ppn
	 * @param post => d_cost
	 */
	public function update_barang_datang() {

		// Permission
		$this->session_lib->check_permission('p_invoice_edit');

		// If null

		#Payment Status => #1: DP, #2: LUNAS, #3: HUTANG

		$invoice_id = $this->input->post('invoice_id');

		$item 		= $this->input->post('item_id');
		$price 		= str_replace(",","",str_replace(".00", "",$this->input->post('price')));
		$totalprice = str_replace(",","",str_replace(".00", "",$this->input->post('totalprice')));
		$grandtotal = str_replace(",","",str_replace(".00", "",$this->input->post('grandtotal')));
		$disc 		= str_replace(",","",str_replace(".00", "",$this->input->post('disc')));
		$d_cost 	= str_replace(",","",str_replace(".00", "",$this->input->post('d_cost')));

		$warehouse 	= $this->db->query("SELECT id,
		 									   name
		 									   FROM warehouse
		 									   WHERE dept_id = " . $this->session->userdata('dept_id'))->result();

		// Validation
		for ($i = 0; $i < count($item); $i++) {

			$y = array();
			foreach ($warehouse as $list) :

				$y['warehouse_' . $list->id] = $this->input->post('warehouse_' . $list->id);

				if ($y['warehouse_' . $list->id][$i] != null) {

					if ($price[$i] == null || $totalprice[$i] == null) {

						echo 'error-null';

						return false;

					}

				}

			endforeach;

		}

		$is_ppn = 0;
		if ($this->input->post('ppn') == '' || $this->input->post('ppn') == NULL || $this->input->post('ppn') == 0) {
			$is_ppn = 1;
		}

		$data = array(
						'dept_id' 			=> $this->session->userdata('dept_id'),
						'invoice_number' 	=> ucwords($this->input->post('invoice_number')),
						'vendor_id' 		=> $this->input->post('vendor_id'),
						//'date_invoice' 	=> date('Y-m-d H:i:s'),
						'total_price' 		=> $grandtotal,
						'disc' 				=> $disc,
						'ppn' 				=> floatval(str_replace(",", "", $this->input->post('ppn'))),
						'is_ppn' 			=> $is_ppn,
						'd_cost'			=> $d_cost,
						'payment_status' 	=> $this->input->post('payment_status'),
						//'created_time' 	=> date('Y-m-d H:i:s'),
						'updated_time' 		=> date('Y-m-d H:i:s'),
						//'creator' 		=> $this->session->userdata('user_id'),
						'updated_by' 		=> $this->session->userdata('user_id')
					);

		$this->db->trans_start();

		$this->db->where('id', $invoice_id);
		$this->db->update('invoice', $data);

		$this->db->query("DELETE FROM invoice_detail WHERE invoice_id = " . $invoice_id);

		for ($i = 0; $i < count($item); $i++) {

			$y = array();
			foreach ($warehouse as $list) :

				$y['warehouse_' . $list->id] = $this->input->post('warehouse_' . $list->id);

				if ($y['warehouse_' . $list->id][$i] != null && $y['warehouse_' . $list->id][$i] != 0) {

					if ($price[$i] != null && $totalprice[$i] != null) {

						# Rumus: price/total price * 100
						$d_cost_item = 0;
						if ($d_cost != null) {
							if ($grandtotal != 0 || $d_cost != 0) {
								$d_cost_item = round(floatval(str_replace(",", "", str_replace(".00", "", $totalprice[$i])))/$grandtotal, 2, PHP_ROUND_HALF_DOWN) * $d_cost;
							}
						}

						$data_detail[] = array(
												'invoice_id' 	=> $invoice_id,
												'item_id'	 	=> $item[$i],
												'warehouse_id' 	=> $list->id,
												'qty'			=> intval($y['warehouse_' . $list->id][$i]),
												'price' 		=> floatval(str_replace(",", "", str_replace(".00", "", $price[$i]))),
												'total_price' 	=> floatval(str_replace(",", "", str_replace(".00", "", $totalprice[$i]))),
												'd_cost' 		=> floatval(str_replace(",", "", str_replace(".00", "", $d_cost_item)))
											);

					}

				}

			endforeach;

		}

		$this->db->insert_batch('invoice_detail', $data_detail);

		// Log

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
	}
	// End of function update_barang_datang

	public function delete_invoice() {

		// Permission
		$this->session_lib->check_permission('p_invoice_delete');

		$this->db->trans_start();

		$this->db->where('invoice_id', $this->input->post('id'));

		$this->db->delete('invoice_detail');

		$this->db->where('id', $this->input->post('id'));

		$this->db->delete('invoice');

		$this->db->where('invoice_id', $this->input->post('id'));

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
	// End of function delete_invoice

}

/* End of file Invoice.php */
/* Location: ./application/controllers/Invoice.php */

