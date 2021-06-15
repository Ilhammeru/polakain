<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Sales
 * Sales as 'Penjualan'
 */
class Sales extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Sales');

	}
	// End of function __construct();

	public function index() {

		// Permission
		$this->session_lib->check_permission('p_sale_add');

		if (! empty($this->session->userdata('template_id'))) {

		} else {
			$this->session->set_userdata('template_id', '0');
		}

		// Log

		$attr = array(
						'customer' 			=> $this->db->query("SELECT id,
															   			name
															   			FROM customer
															   			WHERE dept_id = " . $this->session->userdata('dept_id') . "
															   			ORDER BY name ASC")->result(),
						'payment_method'	=> $this->db->query("SELECT id,
																		CONCAT(name, ' - ', bank_name) AS 'method'
																		FROM payment_method
																		WHERE dept_id = " . $this->session->userdata('dept_id') . "
																		AND status = 1
																		ORDER BY name ASC")->result(),
						'closingDate'		=> $this->closingDate
					);

		$this->layout_lib->default_template('transaction/sales/index', $attr);
		
	}
	// End of function index

	public function input_repeat()
	{

		// Permission
		$this->session_lib->check_permission('p_sale_add');

		if (!empty($this->session->userdata('template_id'))) {
		} else {
			$this->session->set_userdata('template_id', '0');
		}

		// Log

		$attr = array(
			'customer' 			=> $this->db->query("SELECT id,
															   			name
															   			FROM customer
															   			WHERE dept_id = " . $this->session->userdata('dept_id') . "
															   			ORDER BY name ASC")->result(),
			'payment_method'	=> $this->db->query("SELECT id,
																		CONCAT(name, ' - ', bank_name) AS 'method'
																		FROM payment_method
																		WHERE dept_id = " . $this->session->userdata('dept_id') . "
																		AND status = 1
																		ORDER BY name ASC")->result(),
			'closingDate'		=> $this->closingDate
		);

		$this->layout_lib->default_template('transaction/sales/input-repeat', $attr);
	}
	// End of function input_repeat

	function edit($id) {

		// Permission
		$this->session_lib->check_permission('p_sale_edit');

		// Log

		$attr = array(
						'customer' 			=> $this->db->query("SELECT id,
															   			name
															   			FROM customer
															   			WHERE dept_id = " . $this->session->userdata('dept_id') . "
															   			ORDER BY name ASC")->result(),

						'payment_method'	=> $this->db->query("SELECT id,
																		CONCAT(name, ' - ', bank_name) AS 'method'
																		FROM payment_method
																		WHERE dept_id = " . $this->session->userdata('dept_id') . "
																		AND status = 1
																		ORDER BY name ASC")->result(),
						'sales'				=> $this->db->query("SELECT sale.id,
																		customer_ref,
																		customer_id,
																		IF(customer_id != 0, customer.name, NULL) AS 'customer_name',
																		total_price,
																		ppn,
																		d_cost,
																		total_price + ppn + d_cost AS 'grand_total',
																		nominal_bayar,
																		sale_status,
																		payment_method_id,
																		IF(payment_method_id = 'z', 'Tunai', IF(payment_method_id != 0, CONCAT(payment_method.name, ' - ', payment_method.bank_name), NULL)) AS 'payment_method_name'
																		FROM sale
																		LEFT JOIN customer ON customer.id = sale.customer_id AND customer_id != 0
																		LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 0
																		WHERE sale.id = " . $id)->row_array(),
						'id'				=> $id
					);

		$this->layout_lib->default_template('transaction/sales/edit', $attr);

	}
	// End of function edit

	public function print($id) {

		$item = $this->db->query("SELECT item.id,
										 name
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 ORDER BY name ASC")->result();

		$template_item = $this->db->query("SELECT id,
												  CONCAT(brand, ' ', tipe) AS 'template_name',
												  detail
												  FROM template_item
												  WHERE dept_id = " . $this->session->userdata('dept_id'))->result();

		$attr = array(
						'customer' 			=> $this->db->query("SELECT id,
															   			name
															   			FROM customer
															   			WHERE dept_id = " . $this->session->userdata('dept_id') . "
															   			ORDER BY name ASC")->result(),

						'payment_method'	=> $this->db->query("SELECT id,
																		CONCAT(name, ' - ', bank_name) AS 'method'
																		FROM payment_method
																		WHERE dept_id = " . $this->session->userdata('dept_id') . "
																		AND status = 1
																		ORDER BY name ASC")->result(),
						'sales'				=> $this->db->query("SELECT sale.id,
																		customer_ref,
																		customer_id,
																		IF(customer_id != 0, customer.name, NULL) AS 'customer_name',
																		total_price,
																		IF(ppn IS NULL, 0, ppn) AS 'ppn',
																		IF(d_cost IS NULL, 0, d_cost) AS 'd_cost',
																		total_price + ppn + d_cost AS 'grand_total',
																		nominal_bayar,
																		sale_status,
																		payment_method_id,
																		IF(payment_method_id = 'z', 'Tunai', IF(payment_method_id != 0, CONCAT(payment_method.name, ' - ', payment_method.bank_name), NULL)) AS 'payment_method_name',
																		receipt_number,
																		detail,
																		date_kirim,
																		ansena_department.data_detail,
																		ansena_department.fullname
																		FROM sale
																		JOIN sale_detail ON sale_detail.sale_id = sale.id
																		LEFT JOIN customer ON customer.id = sale.customer_id AND customer_id != 0
																		LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 0
																		LEFT JOIN ansena_department ON ansena_department.id = sale.dept_id
																		WHERE sale.id = " . $id)->row_array(),
						'id'				=> $id,
						'item'				=> $item,
						'template_item'		=> $template_item
					);

		$this->load->view('transaction/sales/print', $attr);
		
	}
	// End of function print

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_sale_report');

		// Log

		$attr = array(
						'filterStatus' => $this->filter_status(),
					);

		$this->layout_lib->default_template('transaction/sales/report-data', $attr);
		
	}
	// End of function report_data


	public function filter_status() {

		$html = '<select class="form-control select2" multiple><option value="" disabled>Cari Status</option>';
		$html .= '<option value="1">DP</option>';
		$html .= '<option value="2">Lunas</option>';
		$html .= '<option value="3">Piutang</option>';
		$html .= '</select>';

		return $html;
		
	}
	// End of function filter_status

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
		$columnOrder = array('', 'receipt_number', 'sale.created_time', 'total_price', 'sale_status');

		// Set field search column
	    $columnSearch = array(
    						array(),
    						array(
    							'format' 	=> 'string',
    							'field' 	=> 'receipt_number',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=> 'timestamp',
    							'field' 	=> 'sale.created_time',
    							'type' 		=> 'daterange'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'total_price',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'sale_status',
    							'type' 		=> 'select-multiple'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('created_time' => 'desc');

    	$countTotal = "SELECT id
    						  FROM sale
    						  WHERE dept_id = " . $this->session->userdata('dept_id') . "
    						  AND warehouse_id = " . $this->session->userdata('p_warehouse_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

		$query = "SELECT sale.created_time,
						 receipt_number,
						 total_price + ppn + d_cost AS 'grand_total',
						 sale_status,
						 sale.id,
						 date_lunas,
						 date_dp,
						 due_date,
						 date_kirim,
						 payment_method_id,
						 warehouse.name AS warehouse_name
						 FROM sale
						 JOIN warehouse ON warehouse.id = sale.warehouse_id AND sale.warehouse_id = " . $this->session->userdata('p_warehouse_id') . " ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'sale');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		$sales = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($sales)->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			if ($this->session->userdata('p_sale_delete') == 1) {
				$btnDelete = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger m-1" onclick="delete_sales(' . $rows->id . ')"><i class="fa fa-trash"></i></a>';
			} else {
				$btnDelete = '';
			}

			$btnView = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-info m-1" onclick="display_detail(' . $rows->id . ')"><i class="fa fa-eye"></i></a>';

			if ($rows->date_kirim == NULL) {
			$row[] = $btnView . $btnDelete;
			} else {
			$row[] = $btnView;
			}

			$row[] = $rows->receipt_number;

			$row[] = date_format(date_create($rows->created_time), 'd M Y H:i:s');

			$row[] = '<div class="text-right">Rp ' . number_format($rows->grand_total, 2, '.', ',') . '</div>';

			if ($rows->date_kirim != NULL) {
				$bagde_kirim = '<div class="badge bg-purple">Dikirim</div>';
			} else {
				$bagde_kirim = '';
			}

			if (($rows->sale_status == 1 && $rows->payment_method_id == 'z') || ($rows->sale_status == 1 && $rows->date_dp != NULL)) {

				$row[] = '<div class="badge badge-warning">DP</div> <div class="badge badge-primary">Approved</div>';

			} elseif ($rows->sale_status == 1 && $rows->date_dp == NULL) {

				$row[] = '<div class="badge badge-warning">DP</div> <div class="badge badge-secondary">Menunggu Approval</div>';

			} elseif ($rows->sale_status == 2 && $rows->date_lunas == NULL) {

				$row[] = '<div class="badge badge-success">Lunas</div> <div class="badge badge-secondary">Menunggu Approval</div>';

			} elseif ($rows->sale_status == 2 && $rows->date_lunas != NULL) {

				$row[] = '<div class="badge badge-success">Lunas</div> <div class="badge badge-primary">Approved</div>' . $bagde_kirim;

			} elseif ($rows->sale_status == 3 && $rows->due_date == NULL) {

				$row[] = '<div class="badge badge-danger">Piutang</div> <div class="badge badge-secondary">Menunggu Approval</div>';

			} elseif ($rows->sale_status == 3 && $rows->due_date != NULL) {

				$row[] = '<div class="badge badge-danger">Piutang</div> <div class="badge badge-primary">Approved</div>' . $bagde_kirim;

			}

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
	// End of function server_side_data

	function server_side_data_detail() {

		$query = $this->db->query("SELECT id,
										  detail
										  FROM sale_detail
										  WHERE sale_id = " . $this->input->get('id'))->row_array();

		$item = $this->db->query("SELECT id,
										 code,
										 name
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 ORDER BY name ASC")->result();
		
		$arrayTemplate = array();

		if ($this->session->userdata('dept_id') == 41) {

			$sale = $this->db->query("SELECT template_id, template_pola.name AS template_name 
											FROM sale 
											JOIN template_pola ON template_pola.id = sale.template_id
											WHERE sale.id = " . $this->input->get('id'))->row_array();

			$arrayTemplate['t' . $sale['template_id']] =  $sale['template_name'];
		} else {
			$template_item = $this->db->query("SELECT id,
												  CONCAT(brand, ' ', tipe) AS 'template_name',
												  detail
												  FROM template_item
												  WHERE dept_id = " . $this->session->userdata('dept_id'))->result();

			foreach ($template_item as $row) {
				$arrayTemplate['t' . $row->id] = $row->template_name;
			}
		}

		$detail = json_decode($query['detail'], TRUE);

		$x = array();

		for ($i = 0; $i < count($detail); $i++) {

			foreach ($item as $row) :

				if ($row->id == $detail[$i]['item_id']) {

					$key_t = 't' . $detail[$i]['template_id'];

					if (isset($arrayTemplate[$key_t])) {
						$template_name = $arrayTemplate[$key_t];
						
						if (isset($detail[$i]['template_qty'])) {
							$template_qty = $detail[$i]['template_qty'];
						} else {
							$template_qty = "";
						}

						if (isset($detail[$i]['template_price'])) {
							$template_price = $detail[$i]['template_price'];
						} else {
							$template_price = "";
						}
					} else {
						$template_name = 'Per item';
						$template_qty = "";
						$template_price = "";
					}

					$x[] = array(
									'template_id'	=> $detail[$i]['template_id'],
									'template_name' => $template_name,
									'template_qty'	=> $template_qty,
									'template_price'=> $template_price,
									// 'template_name' => $detail[$i]['template_name'],
									// 'template_qty'	=> $detail[$i]['template_qty'],
									// 'template_price'=> $detail[$i]['template_price'],
									'code'			=> $row->code,
									'name'			=> $row->name,
									'id' 			=> $row->id,
									'qty'	 		=> $detail[$i]['qty'],
									'price'			=> $detail[$i]['price'],
									'total'			=> $detail[$i]['price'] * $detail[$i]['qty'],
									'sale_price_id'	=> $detail[$i]['sale_price_id']
								);

				}

			endforeach;

		}

		echo json_encode($x);

	}
	// End of function server_side_data_detail

	public function load_modal_item() {

		$attr = array(
						'item' => $this->db->query("SELECT item.id,
														   CONCAT(item.name, ' (', itemcolor.name, ')') AS 'item_name',
														   item.code
														   FROM item
														   JOIN itemcolor ON itemcolor.id = JSON_UNQUOTE(JSON_EXTRACT(attribute, '$.color_id'))
														   WHERE dept_id = " . $this->session->userdata('dept_id') . "
														   ORDER BY item.name ASC")->result()
					);

		$this->load->view('transaction/sales/modal-load-item', $attr);
		
	}
	// End of function load_modal_item

	public function load_modal_template() {

		$attr = array(
						'template'  => $this->db->query("SELECT id,
															    brand,
															    tipe,
															    CONCAT(brand, ' ', tipe) AS 'template_name',
															    detail
															    FROM template_item
															    WHERE dept_id = " . $this->session->userdata('dept_id') . "
															    AND (detail IS NOT NULL
															    OR detail != '')
															    ORDER BY brand, tipe ASC")->result_array(),
						'item'		=> $this->db->query("SELECT id,
																code,
																name
																FROM item
																WHERE dept_id = " . $this->session->userdata('dept_id') . "
																ORDER BY name ASC")->result()
					);

		$this->load->view('transaction/sales/modal-load-template', $attr);
		
	}
	// End of function load_modal_template

	public function load_modal_template_pola() {

		$attr = array(
						'template' => $this->db->query("SELECT id, name, price, qty
															FROM template_pola
															WHERE is_active = 1
															ORDER BY name ASC")->result()
		);

		$this->load->view('transaction/sales/modal-load-template-pola', $attr);
	}
	// End of function load_modal_template_pola

	public function insert_item() {

		$param_selected = $this->input->post('param_selected');
		$code = str_replace(' ', '', $this->input->post('code'));

		// Ecer
		if ($param_selected == 1) {

			$query = $this->db->query("SELECT id,
										   name,
										   code
										   FROM item
										   WHERE dept_id = " . $this->session->userdata('dept_id') . "
										   AND code = '" . $code . "'");

			if($query->num_rows() == 0) {

				$output = array(
								'response' 	=> 'error-null'
							);
			} else {

				$result = $query->row_array();

				$sale_price = $this->db->query("SELECT id, JSON_EXTRACT(detail, '$.i" . $result['id'] . "') AS 'price'
													FROM sale_price 
													WHERE dept_id = " . $this->session->userdata('dept_id') . "
													ORDER BY id DESC 
													LIMIT 1");

				if ($sale_price->num_rows() > 0) {

					$sale_price = $sale_price->row_array();

					if ($sale_price['price'] == NULL) {

						$output = array(
									'response' 	=> 'price-null'
								);
					} else {

						$output = array(
										'response' 	=> 'success',
										'id'		=> $result['id'],
										'code' 		=> $result['code'],
										'name'		=> $result['name'],
										'qty'		=> 1,
										'price' 	=> $sale_price['price'],
										'total'		=> $sale_price['price'],
										'template_id'	=> 0,
										'sale_price_id' => $sale_price['id']
									);
					}
				} else {
					$output = array(
									'response' 	=> 'price-null'
								);
				}
			}

		// Paket
		} else {

			if (empty($this->session->userdata('template_id'))) {

				$output = array(
								'response' => 'template-null'
				);
			} else {

				$query = $this->db->query("SELECT id,
												name,
												code
												FROM item
												WHERE dept_id = " . $this->session->userdata('dept_id') . "
												AND code = '" . $code . "'");

				if($query->num_rows() == 0) {

					$output = array(
									'response' 	=> 'error-null'
								);
				} else {

					$result = $query->row_array();

					$template_id 	= $this->session->userdata('template_id');
					$template_price = $this->session->userdata('template_price');
					$template_qty	= $this->session->userdata('template_qty');
					$per_pcs	 	= $template_price / $template_qty;

					$output = array(
									'response' 	=> 'success',
									'id'		=> $result['id'],
									'code' 		=> $result['code'],
									'name'		=> $result['name'],
									'qty'		=> 1,
									'price' 	=> $per_pcs,
									'total'		=> $per_pcs,
									'template_id'	=> $template_id,
									'template_price'	=> $per_pcs,
									'template_qty' => $template_qty,
									'sale_price_id' => 0
								);
				}
			}

		}

		echo json_encode($output);
	}
	// End of function insert_item

	public function insert_template($id, $qty) {

		$result = $this->db->query("SELECT id,
										   CONCAT('Paket ', brand, ' - ', tipe) AS 'name',
										   detail
										   FROM template_item
										   WHERE id = " . $id)->row_array();

		$item = $this->db->query("SELECT id,
										 name,
										 code
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 ORDER BY name ASC")->result();

		$sale_price = $this->db->query("SELECT id, detail
											   FROM sale_price 
											   WHERE dept_id = " . $this->session->userdata('dept_id') . "
											   ORDER BY id DESC 
											   LIMIT 1")->result_array();


		$y = json_decode($sale_price[0]['detail'], TRUE);

		$detail = json_decode($result['detail'], TRUE);

		$x = array();
		$templatePrice = 0;

		foreach ($detail as $key => $i) {

			if (isset($y[$key])) {
				$templatePrice += $y[$key] * $i;
			}

		}

		foreach ($item as $row) :

			$key = 'i' . $row->id;

			if (isset($detail[$key])) {

				if (isset($y[$key])) {
					$price = $y[$key];

					$x[] = array(
							'id'			=> $row->id,
							'template_name'	=> $result['name'],
							'template_id'	=> $result['id'],
							'template_qty'	=> $qty,
							'template_price'=> $templatePrice,
							'name' 			=> $row->name,
							'code' 			=> $row->code,
							'qty'			=> $detail[$key] * $qty,
							'price'			=> $price,
							'total'			=> $detail[$key] * $price * $qty,
							'sale_price_id'	=> $sale_price[0]['id']
						);

				} else {

					$x = array(
								'response' => 'price-null',
								'name' => $row->name
							);

				}

			}

		endforeach;

		echo json_encode($x);

	}
	// End of function insert_template

	public function save_sales() {

		// Permission
		$this->session_lib->check_permission('p_sale_add');

		if (! $this->input->post('item_id')) {

			echo 'error';

		} else {

			$customer_ref = $this->input->post('customer_ref');
			$visitor_id = $this->input->post('visitor_id');
			$visitor_phone = $this->input->post('visitor_phone');

			if (! $this->input->post('payment_method_id')) {
				$payment_method_id = 0;
			} else {
				$payment_method_id = $this->input->post('payment_method_id');
			}

			if ($customer_ref == 'auto') {

				$result = $this->db->query("SELECT customer_ref
												   FROM sale
												   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   ORDER BY id DESC
												   LIMIT 1")->row_array();

				if ($result == null) {
					$customer_ref	= 1;
					$customer_id 	= 0;
				} else {
					$customer_ref 	= intval($result['customer_ref']) + 1;
					$customer_id 	= 0;
				}

			} else {

				$customer = $this->db->query("SELECT id, name, phone 
													 FROM customer 
													 WHERE dept_id = " . $this->session->userdata('dept_id') . "
													 AND name = '" . $customer_ref . "'")->row_array();

				if ($customer == null) {

					$data_customer = array(
											'name'			=> $customer_ref,
											'phone'			=> $visitor_phone,
											'dept_id'		=> $this->session->userdata('dept_id'),
											'created_time' 	=> date('Y-m-d H:i:s'),
											'updated_time' 	=> date('Y-m-d H:i:s'),
											'creator'		=> $this->session->userdata('user_id'),
											'updated_by' 	=> $this->session->userdata('user_id')
										);

					$this->db->insert('customer', $data_customer);

					$customer_id = $this->db->insert_id();

				} else {

					$customer_id 	= $customer['id'];
					$visitor_phone 	= $customer['phone'];
					$visitor_name 	= $customer['name'];

					//get visitor id 
					$search_id = $this->db->query("SELECT visitor_id 
														FROM sale 
														WHERE customer_id = $customer_id
														AND visitor_phone = '$visitor_phone'")->row_array();

					if ($search_id == NULL ) {
						//get visitor id from visitor database 
						$vDb = $this->load->database('visitor', TRUE);

						$result_visit = $vDb->query("SELECT id_visit 
															FROM visit 
															WHERE hp = '$visitor_phone'
															AND nama = '$visitor_name'")->row_array();

						if ($result_visit == NULL) {
							echo 'visitor-null';
							die;
						} else {
							$visitor_id = $result_visit['id_visit'];
						}
					} else {
						$visitor_id = $search_id['visitor_id'];
					}					

				}

			}

			//update visitor's database 
			$data_visitor = [
				'tanggal_join'	=> date('Y-m-d H:i:s')
			];

			$db_visit = $this->load->database('visitor', TRUE);
			$db_visit->where('id_visit', $visitor_id);
			$db_visit->update('visit', $data_visitor);

			$db_visit->close();

			$receipt_number = 'INV/' . strtotime(date('Ymd')) . '/' . romanic_number(date('y')) . romanic_number(date('y')) . '/' . romanic_number(date('m')) . substr(strtotime(date('Y-m-d H:i:s')), -8);

			$pay_status 	= $this->input->post('pay_status');
			$subtotal 		= $this->input->post('subtotal');
			$ppn			= $this->input->post('ppn');
			$d_cost			= floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('d_cost'))));
			$pay 			= floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('pay'))));

			$nominal_dp = 0;
			$date_piutang = null;
			$date_lunas = null;
			$date_dp = null;

			if ($pay_status == 2 && $payment_method_id == 'z') {
				$date_lunas = date('Y-m-d H:i:s');
			}

			if ($pay_status == 1 && $payment_method_id == 'z') {
				$date_dp = date('Y-m-d H:i:s');
				$nominal_dp = $pay;
			}

			if ($pay_status == 3) {
				$date_piutang = date('Y-m-d H:i:s');
			}

			$data = array(
							'dept_id'			=> $this->session->userdata('dept_id'),
							'warehouse_id'      => $this->session->userdata('p_warehouse_id'),
							'customer_ref'		=> $customer_ref,
							'customer_id'  		=> $customer_id,
							'visitor_id'		=> $visitor_id,
							'visitor_phone'		=> $visitor_phone,
							'receipt_number'	=> $receipt_number,
							'sale_status'		=> $pay_status,
							'total_price'		=> $subtotal,
							'ppn'				=> $ppn,
							'd_cost'			=> $d_cost,
							'nominal_bayar' 	=> $pay,
							'nominal_dp' 		=> $nominal_dp,
							'payment_method_id' => $payment_method_id,
							'date_piutang'		=> $date_piutang,
							'date_lunas'		=> $date_lunas,
							'date_dp' 			=> $date_dp,
							'created_time'		=> date('Y-m-d H:i:s'),
							'updated_time'		=> date('Y-m-d H:i:s'),
							'creator'			=> $this->session->userdata('user_id'),
							'updated_by'		=> $this->session->userdata('user_id')
						);

			if ($this->session->userdata('dept_id') == 41) {
				$data['template_id'] = $this->session->userdata('template_id');
			}

			$item_id 	= $this->input->post('item_id');
			$item_qty	= $this->input->post('item_qty');
			$item_price = $this->input->post('item_price');

			$template_id 	= $this->input->post('template_id');
			$template_qty 	= $this->input->post('template_qty');
			$template_price	= $this->input->post('template_price');

			$sale_price_id = $this->input->post('sale_price_id');

			$this->db->trans_start();

			$this->db->insert('sale', $data);
			$insert_id = $this->db->insert_id();

			for ($i = 0; $i < count($item_id); $i++) {

				$detail[] = array(
								'template_id'	=> $template_id[$i],
								'template_qty'	=> $template_qty[$i],
								'template_price'=> $template_price[$i],
								'item_id' 		=> $item_id[$i],
								'qty'	 		=> $item_qty[$i],
								'price'			=> $item_price[$i],
								'sale_price_id'	=> $sale_price_id[$i]
							);

			}

			$data_detail = array(
									'sale_id' 	=> $insert_id,
									'detail' 	=> json_encode($detail)
								);
			
			$this->db->insert('sale_detail', $data_detail);

			$record[date('Y-m-d H:i:s')] = array(
												'status_menu' 	=> 0,
												'act'			=> 1,
												'user_id'		=> $this->session->userdata('user_id')
											);

			$data_record = array(
									'sale_id'	=> $insert_id,
									'detail' 	=> json_encode($record)
								);

			$this->db->insert('sale_record', $data_record);
			
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
	// End of function save_sales

	public function edit_sales($id) {
		
		// Permission
		$this->session_lib->check_permission('p_sale_edit');

		if (! $this->input->post('item_id')) {

			echo 'error';

		} else {

			$customer_ref = $this->input->post('customer_ref');

			if (! $this->input->post('payment_method_id')) {
				$payment_method_id = 0;
			} else {
				$payment_method_id = $this->input->post('payment_method_id');
			}

			if ($customer_ref == 'auto') {

				$result = $this->db->query("SELECT customer_ref
												   FROM sale
												   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   ORDER BY id DESC
												   LIMIT 1")->row_array();

				if ($result == null) {
					$customer_ref	= 1;
					$customer_id 	= 0;
				} else {
					$customer_ref 	= intval($result['customer_ref']) + 1;
					$customer_id 	= 0;
				}

			} else {

				$customer = $this->db->query("SELECT id 
													 FROM customer 
													 WHERE dept_id = " . $this->session->userdata('dept_id') . "
													 AND name = '" . $customer_ref . "'")->row_array();

				if ($customer == null) {

					$data_customer = array(
											'name'			=> $customer_ref,
											'dept_id'		=> $this->session->userdata('dept_id'),
											'created_time' 	=> date('Y-m-d H:i:s'),
											'updated_time' 	=> date('Y-m-d H:i:s'),
											'creator'		=> $this->session->userdata('user_id'),
											'updated_by' 	=> $this->session->userdata('user_id')
										);

					$this->db->insert('customer', $data_customer);

					$customer_id = $this->db->insert_id();

				} else {

					$customer_id = $customer['id'];

				}

			}

			$pay_status 	= $this->input->post('pay_status');
			$subtotal 		= $this->input->post('subtotal');
			$ppn			= $this->input->post('ppn');
			$d_cost			= floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('d_cost'))));
			$pay 			= floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('pay'))));

			$nominal_dp = 0;
			$date_piutang = null;
			$date_lunas = null;
			$date_dp = null;

			if ($pay_status == 2 && $payment_method_id == 'z') {
				$date_lunas = date('Y-m-d H:i:s');
			}

			if ($pay_status == 1 && $payment_method_id == 'z') {
				$date_dp = date('Y-m-d H:i:s');
				$nominal_dp = $pay;
			}

			if ($pay_status == 3) {
				$date_piutang = date('Y-m-d H:i:s');
			}

			$data = array(
							'customer_ref'		=> $customer_ref,
							'customer_id'  		=> $customer_id,
							'sale_status'		=> $pay_status,
							'total_price'		=> $subtotal,
							'ppn'				=> $ppn,
							'd_cost'			=> $d_cost,
							'nominal_bayar' 	=> $pay,
							'nominal_dp'		=> $nominal_dp,
							'payment_method_id' => $payment_method_id,
							'date_piutang'		=> $date_piutang,
							'date_lunas'		=> $date_lunas,
							'date_dp' 			=> $date_dp,
							'updated_time'		=> date('Y-m-d H:i:s'),
							'updated_by'		=> $this->session->userdata('user_id')
						);

			$item_id 	= $this->input->post('item_id');
			$item_qty	= $this->input->post('item_qty');
			$item_price = $this->input->post('item_price');

			$template_id 	= $this->input->post('template_id');
			$template_qty 	= $this->input->post('template_qty');
			$template_price = $this->input->post('template_price');

			$sale_price_id = $this->input->post('sale_price_id');

			$this->db->trans_start();

			$this->db->where('id', $id);
			$this->db->update('sale', $data);
			$insert_id = $id;

			$this->db->where('sale_id', $insert_id);
			$this->db->delete('sale_detail');

			for ($i = 0; $i < count($item_id); $i++) {

				$detail[] = array(
								'template_id'	=> $template_id[$i],
								'template_qty'	=> $template_qty[$i],
								'template_price'=> $template_price[$i],
								'item_id' 		=> $item_id[$i],
								'qty'	 		=> $item_qty[$i],
								'price'			=> $item_price[$i],
								'sale_price_id'	=> $sale_price_id[$i]
							);

			}

			$data_detail = array(
									'sale_id' 	=> $insert_id,
									'detail' 	=> json_encode($detail)
								);
			
			$this->db->insert('sale_detail', $data_detail);

			$this->db->query("UPDATE sale_record SET detail = JSON_SET(detail, '$." . date('Y-m-d H:i:s') . "', 
																	JSON_OBJECT('status_menu', 0, 'act', 2, 'user_id', '" . $this->session->userdata('user_id') . "'))
												 WHERE sale_id = " . $insert_id);
			
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
	// End of function edit_sales

	public function load_display_detail() {

		$attr = array(
						'closingDate' => $this->closingDate,
						'receipt' => $this->db->query("SELECT sale.id,
															  receipt_number,
															  customer_id,
															  customer_ref,
															  IF(sale.customer_id = 0, CONCAT('#Customer', sale.customer_ref), customer.name) AS 'customer_name',
															  nominal_bayar,
															  total_price,
															  sale_status,
															  due_date,
															  DATEDIFF(due_date, NOW()) AS 'datediff',
															  d_cost,
															  ppn,
															  total_price + ppn + d_cost AS 'grand_total',
															  sale_price_ref,
															  sale.created_time,
															  sale.creator,
															  payment_method_id,
															  date_lunas,
															  date_dp,
															  due_date,
															  date_piutang,
															  date_kirim,
															  CASE
															  WHEN sale_status = 1 THEN date_dp
															  WHEN sale_status = 2 THEN date_lunas
															  WHEN sale_status = 3 THEN due_date
															  END AS 'date_pay',
															  CASE
															  WHEN payment_method_dp_lunas IS NOT NULL THEN IF(sale.payment_method_dp_lunas = 'z', 'Tunai', IF(sale.payment_method_dp_lunas != 0, CONCAT(dp_lunas.name, ' - ', dp_lunas.bank_name), NULL))
															  ELSE IF(sale.payment_method_id = 'z', 'Tunai', IF(sale.payment_method_id != 0, CONCAT(payment_method.name, ' - ', payment_method.bank_name), NULL))
															  END AS 'payment_method_name'
															  FROM sale
															  LEFT JOIN customer ON customer.id = sale.customer_id AND sale.customer_id != 0
															  LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 0
															  LEFT JOIN payment_method dp_lunas ON dp_lunas.id = sale.payment_method_dp_lunas AND sale.payment_method_dp_lunas IS NOT NULL
															  WHERE sale.id = " . $this->input->get('id'))->row_array()
					);

		$this->load->view('transaction/sales/display-detail', $attr);
		
	}
	// End of function load_display_detail

	public function delete_sales() {

		// Permission
		$this->session_lib->check_permission('p_sale_delete');

		$this->db->trans_start();

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('sale');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function delete_sales

	/**
	 * @param post => id
	 */
	public function selected_template() {

		$id = $this->input->post('id');

		if ($id == 0) {
			$data  = array(
							'template_id'		=> '0',
							'template_name' 	=> 'Eceran',
							'template_price'	=> 0,
							'template_qty'		=> '-'
			);

		} else {

			$query = $this->db->query("SELECT id, name, price, qty
											FROM template_pola
											WHERE id = " . $id)->row_array();

			$data  = array(
							'template_id'		=> $query['id'],
							'template_name' 	=> $query['name'],
							'template_price'	=> $query['price'],
							'template_qty'		=> $query['qty']
			);
		}
		
		$this->session->set_userdata($data);

		echo json_encode($data);
	}
	// End of function selected_template

	/**
	 * @param post => grandtotal
	 */
	public function validate_template() {

		$grandtotal = $this->input->post('grandtotal');

		$sess_grandtotal = $this->session->userdata('template_price');

		if ($grandtotal == $sess_grandtotal) {
			echo 'matched';
		} else {
			echo 'not-matched';
		}
	}
	// End of function validate_template

	public function autocomplete() {
		$data = $_GET['term'];

		$db = $this->load->database('visitor', TRUE);

		$query = $db->query("SELECT hp
									FROM visit
									WHERE hp LIKE '%$data%'");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$hp[] = $row->hp;
			}
		}

		$db->close();

		echo json_encode($hp);
	}

	public function get_visitor() {
		$data = $_POST['data'];

		$db = $this->load->database('visitor', TRUE);

		$query = $db->query("SELECT id_visit, nama
								FROM visit 
								WHERE hp = '$data'");

		$data = array();
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$data['id'] 		= $result['id_visit'];
			$data['name']		= $result['nama'];
			$data['message']	= 'success';
		} else {
			$data['message']	= 'data-null';
		}

		echo json_encode($data);
	}
}
/* End of file Sales.php */
/* Location: ./application/controllers/Sales.php */
