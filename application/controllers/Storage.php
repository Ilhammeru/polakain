<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Storage
 */
class Storage extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Storage');

	}
	// End of function __construct();

	public function packing_list() {

		// Permission
		$this->session_lib->check_permission('p_storage_report');

		$attr = array(
						'closingDate' => $this->closingDate
					);

		$this->layout_lib->default_template('transaction/storage/packing-list', $attr);
		
	}
	// End of function packing_list

	public function move_item() {

		// Permission
		$this->session_lib->check_two_permission('or', 'p_move_item_add', 'p_move_item_report');

		$attr = array(
						'closingDate' => $this->closingDate,
						'warehouse' => $this->db->query("SELECT id,
																name
																FROM warehouse
																WHERE dept_id = " . $this->session->userdata('dept_id') . "
																AND id != " . $this->session->userdata('p_warehouse_id') . "
																ORDER BY name ASC")->result()
					);

		$this->layout_lib->default_template('transaction/storage/move-item', $attr);
		
	}
	// End of function move_item

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function server_side_data() {

		$query = $this->db->query("SELECT sale.id,
										  receipt_number,
										  sale.created_time,
										  nominal_bayar,
										  sale_status,
										  customer_ref
										  FROM sale
										  LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
										  WHERE sale.dept_id = " . $this->session->userdata('dept_id') . "
										  AND sale.warehouse_id = " . $this->session->userdata('p_warehouse_id') . "
										  AND date_kirim IS NULL
										  AND (date_lunas IS NOT NULL OR due_date IS NOT NULL)
										  ORDER BY sale.date_lunas, due_date DESC");

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->receipt_number;

			$row[] = $rows->customer_ref;

			$row[] = date('d.m.Y H:i', strtotime($rows->created_time));

			$row[] = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-info" onclick="display_detail(' . $rows->id . ', 0)"><i class="fa fa-eye"></i></a>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data

	public function server_side_data_history() {

		$query = $this->db->query("SELECT sale.id,
										  receipt_number,
										  sale.created_time,
										  nominal_bayar,
										  sale_status,
										  customer_ref
										  FROM sale
										  LEFT JOIN payment_method ON payment_method.id = sale.payment_method_id AND sale.payment_method_id != 'z'
										  WHERE sale.dept_id = " . $this->session->userdata('dept_id') . "
										  AND sale.warehouse_id = 5
										  AND DATE_FORMAT(date_move, 'Y-m-d') = DATE_FORMAT(NOW(), 'Y-m-d')
										  ORDER BY sale.date_lunas, date_piutang DESC");

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = '<div class="text-center">' . $rows->receipt_number . '</div>';

			$row[] = '<div class="text-center">' . $rows->customer_ref . '</div>';

			$row[] = date('d.m.Y H:i', strtotime($rows->created_time));

			$row[] = '<a href="javascript:void(0)" class="btn btn-sm btn-outline-info" onclick="display_detail(' . $rows->id . ', 1)"><i class="fa fa-eye"></i></a>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_history

	public function load_display_detail() {

		$attr = array(
						'closingDate' => $this->closingDate,
						'x' => $this->input->get('x'),
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

		$this->load->view('transaction/storage/display-detail', $attr);
		
	}
	// End of function load_display_detail

	public function save_data() {

		// Permission
		$this->session_lib->check_permission('p_storage_approval');

		$this->db->trans_start();

		if ($this->session->userdata('p_warehouse_id') == 4) {

			$data = array(
						'date_move'	=> date('Y-m-d H:i:s'),
						'updated_time'	=> date('Y-m-d H:i:s'),
						'updated_by' 	=> $this->session->userdata('user_id')
					);		

		} else {

			$data = array(
						'date_kirim'	=> date('Y-m-d H:i:s'),
						'updated_time'	=> date('Y-m-d H:i:s'),
						'updated_by' 	=> $this->session->userdata('user_id')
					);		

		}

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('sale', $data);

		$this->db->query("UPDATE sale_record SET detail = JSON_SET(detail, '$." . date('Y-m-d H:i:s') . "', 
														JSON_OBJECT('status_menu', 4, 'act', 1, 'user_id', '" . $this->session->userdata('user_id') . "'))
									 			WHERE sale_id = " . $this->input->post('id'));

		$data_income = array(
							'dept_id' 		=> $this->session->userdata('dept_id'),
							'sale_id' 		=> $this->input->post('id'),	
							'nominal' 		=> floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('nominal')))),
							'ppn_keluar' 	=> floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('ppn')))),
							'titipan' 		=> floatval(str_replace(",", "", str_replace(".00", "", $this->input->post('d_cost')))),
							'created_time'	=> date('Y-m-d H:i:s'),
							'updated_time'	=> date('Y-m-d H:i:s'),
							'creator'		=> $this->session->userdata('user_id'),
							'updated_by'	=> $this->session->userdata('user_id')
						);

		$this->db->insert('income', $data_income);

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function save_data

	public function cancel_kirim() {

		// Permission
		$this->session_lib->check_permission('p_storage_cancel');

		$this->db->trans_start();

		$data = array(
						'date_kirim'	=> NULL,
						'updated_time'	=> date('Y-m-d H:i:s'),
						'updated_by' 	=> $this->session->userdata('user_id')
					);		

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('sale', $data);

		$this->db->where('sale_id', $this->input->post('id'));
		$this->db->delete('income');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function cancel_kirim

	public function insert_item() {

		$query = $this->db->query("SELECT id,
										   name,
										   code
										   FROM item
										   WHERE dept_id = " . $this->session->userdata('dept_id') . "
										   AND code = '" . $this->input->post('code') . "'");

		if($query->num_rows() == 0) {

			$output = array(
							'response' 	=> 'error-null'
						);

		} else {

			$result = $query->row_array();

			$output = array(
							'response' 	=> 'success',
							'id'		=> $result['id'],
							'code' 		=> $result['code'],
							'name'		=> $result['name'],
							'qty'		=> 1,
							'template_id'	=> 0
						);

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

		$detail = json_decode($result['detail'], TRUE);

		$x = array();

		foreach ($item as $row) :

			$key = 'i' . $row->id;

			if (isset($detail[$key])) {

				$x[] = array(
							'id'			=> $row->id,
							'template_name'	=> $result['name'],
							'template_id'	=> $result['id'],
							'name' 			=> $row->name,
							'code' 			=> $row->code,
							'qty'			=> $detail[$key] * $qty
						);

			}

		endforeach;

		echo json_encode($x);

	}
	// End of function insert_template

	public function load_modal_item() {

		$attr = array(
						'item' => $this->db->query("SELECT item.id,
														   item.name AS 'item_name',
														   item.code
														   FROM item
														   WHERE dept_id = " . $this->session->userdata('dept_id') . "
														   ORDER BY name ASC")->result()
					);

		$this->load->view('transaction/storage/modal-load-item', $attr);
		
	}
	// End of function load_modal_item

	public function load_modal_template() {

		$attr = array(
						'template'  => $this->db->query("SELECT id,
															    brand,
															    tipe,
															    detail,
															    CONCAT(brand, ' ', tipe) AS 'template_name'
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

		$this->load->view('transaction/storage/modal-load-template', $attr);
		
	}
	// End of function load_modal_template

	public function save_move_item() {

		// Permission
		$this->session_lib->check_permission('p_move_item_add');

		if (! $this->input->post('item_id')) {

			echo 'error';

		} else {

			$warehouse_id = $this->input->post('warehouse_id');

			$item_id 	= $this->input->post('item_id');
			$item_qty	= $this->input->post('item_qty');
			$template_id = $this->input->post('template_id');

			$this->db->trans_start();

			for ($i = 0; $i < count($item_id); $i++) {

				$detail[] = array(
								'template_id' => $template_id[$i],
								'item_id' => $item_id[$i],
								'qty' => $item_qty[$i]
							);

			}

			$data = array(
							'dept_id' => $this->session->userdata('dept_id'),
							'from_warehouse' => $this->session->userdata('p_warehouse_id'),
							'target_warehouse' => $this->input->post('warehouse_id'),
							'detail' => json_encode($detail),
							'created_time' => date('Y-m-d H:i:s'),
							'updated_time' => date('Y-m-d H:i:s'),
							'creator' => $this->session->userdata('user_id'),
							'updated_by' => $this->session->userdata('user_id')
						);

			$this->db->insert('move_item', $data);

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
	// End of function save_move_item

	public function server_side_data_list_move_item() {

		$query = $this->db->query("SELECT move_item.id,
										  from_warehouse,
										  target_warehouse,
										  w1.name AS 'from_warehouse_name',
										  w2.name AS 'target_warehouse_name',
										  move_item.created_time
										  FROM move_item
										  LEFT JOIN warehouse w1 ON w1.id = move_item.from_warehouse
										  LEFT JOIN warehouse w2 ON w2.id = move_item.target_warehouse
										  WHERE move_item.dept_id = " . $this->session->userdata('dept_id'));

		$result = $query->result();

		$data = array();

		foreach ($result as $rows) :

			$row = array();
			
			$row[] = date('d.m.Y H:i', strtotime($rows->created_time));

			$row[] = '<div class="text-center">' . $rows->from_warehouse_name . '</div>';

			$row[] = '<div class="text-center">' . $rows->target_warehouse_name . '</div>';

			$row[] = '<a href="javascript:void(0)" onclick="display_detail(' . $rows->id . ')" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);

		
	}
	// End of function server_side_data_list_move_item

	public function load_detail_move_item() {

		$id = $this->input->get('id');

		$query = $this->db->query("SELECT move_item.id,
										  from_warehouse,
										  target_warehouse,
										  w1.name AS 'from_warehouse_name',
										  w2.name AS 'target_warehouse_name',
										  move_item.created_time,
										  detail
										  FROM move_item
										  LEFT JOIN warehouse w1 ON w1.id = move_item.from_warehouse
										  LEFT JOIN warehouse w2 ON w2.id = move_item.target_warehouse
										  WHERE move_item.id = " . $id);

		$item = $this->db->query("SELECT id,
										 name,
										 code
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 ORDER BY name ASC")->result();

		$template = $this->db->query("SELECT id,
											 CONCAT(brand, ' ', tipe) AS 'template_name'
											 FROM template_item
											 WHERE dept_id = " . $this->session->userdata('dept_id'))->result();
		
		$attr = array(
						'move_item' => $query->row_array(),
						'item' => $item,
						'template' => $template,
						'closingDate' => $this->closingDate
					);

		$this->load->view('transaction/storage/display-detail-move-item', $attr);

	}
	// End of function load_detail_move_item

	public function delete_move_item() {

		// Permission
		$this->session_lib->check_permission('p_move_item_delete');

		$this->db->trans_start();

		$this->db->where('id', $this->input->post('id'));

		$this->db->delete('move_item');

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function delete_move_item

	public function move_storage_with_inv() {
		$this->db->trans_start();

		$sale_id = $this->input->post('id');

		$query = $this->db->query("SELECT detail
									FROM sale_detail
									WHERE sale_id  = " .  $sale_id)->row_array();

		$detail  = json_decode($query['detail'], TRUE);

		$dataMove = array();
		for ($i = 0;  $i < count($detail); $i++) {

			$dataMove[$i] = array(
									'template_id' => $detail[$i]['template_id'],
									'item_id' => $detail[$i]['item_id'],
									'qty' => $detail[$i]['qty']
			);
		}

		$data = array(
						'dept_id' => $this->session->userdata('dept_id'),
						'from_warehouse' => $this->session->userdata('p_warehouse_id'),
						'target_warehouse' => 5,
						'param' => 1,
						'detail' => json_encode($dataMove),
						'created_time' => date('Y-m-d H:i:s'),
						'updated_time' => date('Y-m-d H:i:s'),
						'creator' => $this->session->userdata('user_id'),
						'updated_by' => $this->session->userdata('user_id')
		);

		$this->db->insert('move_item', $data);

		$dataInv = array(
							'warehouse_note' => json_encode(array('from' => $this->session->userdata('p_warehouse_id'), 'datemove' => date('Y-m-d H:i:s'))),
							'warehouse_id' => 5,
							'updated_time' =>  date('Y-m-d H:i:s'),
							'updated_by' => $this->session->userdata('user_id')
		);

		$this->db->where('id', $sale_id);
		$this->db->update('sale', $dataInv);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			echo 'error';
		} else {
			$this->db->trans_commit();
			echo 'success';
		}
	}
	// End of function move_storage_with_inv

}
/* End of file Storage.php */
/* Location: ./application/controllers/Storage.php */
