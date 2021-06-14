<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Update Stock
 */
class Update_stock extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Update Stock');

	}
	// End of function __construct();

	function index() {

		// Permission
		$this->session_lib->check_two_permission('or', 'p_update_stock_add', 'p_update_stock_edit');

		$date_now = $this->input->get('date');
		$date_yesterday = date('Y-m-d', strtotime('-1 days', strtotime($date_now)));

		/**
		 * Stock Awal
		 * Diambil dari data stock sebelumnya
		 */

		$queryStockAwal = $this->db->query("SELECT id, stock_real
													FROM stock
													WHERE dept_id = " . $this->session->userdata('dept_id') . "
													AND warehouse_id =  " . $this->session->userdata('p_warehouse_id') . "
													AND DATE_FORMAT(stock_date, '%Y-%m-%d') = '" . $date_yesterday . "'");

		$stock_awal = array();
		if ($queryStockAwal->num_rows() > 0) {
			$resultStockAwal = $queryStockAwal->row_array();
			$stock_awal = json_decode($resultStockAwal['stock_real']);

			foreach ($stock_awal as $row) :
				$key_i = 'i' . $row->item_id;
				$stock_awal[$key_i] = $row->qty;
			endforeach;
		}

		/**
		 * Stock Real, Stock Hilang, Stock Rusak
		 * Diambil dari data stock hari ini jika data sudah diinput
		 */

		$queryStock = $this->db->query("SELECT stock_real, stock_hilang, stock_rusak
													FROM stock
													WHERE dept_id = " . $this->session->userdata('dept_id') . "
													AND warehouse_id =  " . $this->session->userdata('p_warehouse_id') . "
													AND DATE_FORMAT(stock_date, '%Y-%m-%d') = '" . $date_now . "'");

		$stock_real = array();
		$stock_hilang = array();
		$stock_rusak = array();

		$stock_now = $queryStock->num_rows();

		if ($stock_now > 0) {

			$resultStock = $queryStock->row_array();

			$stock_real = json_decode($resultStock['stock_real']);
			$stock_hilang = json_decode($resultStock['stock_hilang']);
			$stock_rusak = json_decode($resultStock['stock_rusak']);

			foreach ($stock_real as $row) :
				$key_i = 'i' . $row->item_id;
				$stock_real[$key_i] = $row->qty;
			endforeach;

			foreach ($stock_hilang as $row) :
				$key_i = 'i' . $row->item_id;
				$stock_hilang[$key_i] = $row->qty;
			endforeach;

			foreach ($stock_rusak as $row) :
				$key_i = 'i' . $row->item_id;
				$stock_rusak[$key_i] = $row->qty;
			endforeach;
		}

		$results = $this->db->query("SELECT id,
											    code,
											    name
											    FROM item
											    WHERE dept_id = " . $this->session->userdata('dept_id') . "
											    ORDER BY name ASC")->result();

		$attr = array(
						'result' => $results,
						'stock_now' => $stock_now,
						'stock_awal' => $stock_awal,
						'stock_real' => $stock_real,
						'stock_hilang' => $stock_hilang,
						'stock_rusak' => $stock_rusak,
						'date' => $this->input->get('date'),
						'closingDate' => $this->closingDate
					);

		$this->layout_lib->default_template('transaction/update_stock/index', $attr);

	}
	// End of function index

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function get_data_stock_in() {

		$query = "SELECT SUM(qty) AS 'total'
						 FROM invoice_detail
						 JOIN invoice ON invoice.id = invoice_detail.invoice_id
						 WHERE invoice.dept_id = " . $this->session->userdata('dept_id') . "
						 AND invoice_detail.warehouse_id = " . $this->session->userdata('p_warehouse_id') . "
						 AND DATE_FORMAT(invoice.date_invoice, '%Y-%m-%d') = '" . $this->input->post('date') . "'
						 AND item_id = " . $this->input->post('id') . "
						 GROUP BY item_id, warehouse_id"; 

		$result = $this->db->query($query)->row_array();

		$total = 0;

		if ($result != null) {
			$total = $result['total'];
		}

		$query = "SELECT detail
					FROM move_item
					WHERE dept_id = " . $this->session->userdata('dept_id') . "
					AND target_warehouse =  " . $this->session->userdata('p_warehouse_id') . "
					AND DATE_FORMAT(created_time, '%Y-%m-%d') = '" . $this->input->post('date') . "'";

		$result = $this->db->query($query)->result();

		if ($result > 0) {
			foreach ($result as $row) :

				$data_stock = json_decode($row->detail);
				foreach ($data_stock as $x) :
					if ($x->item_id == $this->input->post('id')) {
						$total += $x->qty;	
					}
				endforeach;
			endforeach;
		}

		echo $total;
	}
	// End of function get_data_stock_in

	public function get_data_stock_out() {

		$query = "SELECT detail
						 FROM sale_detail
						 JOIN sale ON sale.id = sale_detail.sale_id
						 WHERE dept_id = " . $this->session->userdata('dept_id') . "
						 AND warehouse_id = " . $this->session->userdata('p_warehouse_id') . "
						 AND DATE_FORMAT(date_kirim, '%Y-%m-%d') = '" . $this->input->post('date') . "'";

		$result = $this->db->query($query)->result();

		$stock_out = 0;

		if ($result > 0) {

			foreach ($result as $row) :

				$data_stock = json_decode($row->detail);
				foreach ($data_stock as $x) :
					if ($x->item_id == $this->input->post('id')) {
						$stock_out += $x->qty;	
					}
				endforeach;
			endforeach;
		}

		echo $stock_out;
	}
	// End of function get_data_stock_out

	public function get_data_stock_move() {

		$query = "SELECT detail
						 FROM move_item
						 WHERE dept_id = " . $this->session->userdata('dept_id') . "
						 AND from_warehouse =  " . $this->session->userdata('p_warehouse_id') . "
						 AND DATE_FORMAT(created_time, '%Y-%m-%d') = '" . $this->input->post('date') . "'";

		$result = $this->db->query($query)->result();

		$stock_out = 0;

		if ($result > 0) {

			foreach ($result as $row) :

				$data_stock = json_decode($row->detail);

				foreach ($data_stock as $x) :

					if ($x->item_id == $this->input->post('id')) {

						$stock_out += $x->qty;	

					}

				endforeach;

			endforeach;

		}

		echo $stock_out;
		
	}
	// End of function get_data_stock_move

	public function save_data() {

		// Permission
		$this->session_lib->check_two_permission('or', 'p_update_stock_add', 'p_update_stock_edit');

		$count_data = $this->input->post('count_data');
		$date = $this->input->post('date');

		$item_id = $this->input->post('item_id');
		$stock_awal = $this->input->post('stock_awal');
		$stock_in = $this->input->post('stock_in');
		$stock_out = $this->input->post('stock_out');
		$stock_move = $this->input->post('stock_move');
		$stock_hilang = $this->input->post('stock_hilang');
		$stock_rusak = $this->input->post('stock_rusak');
		$stock_akhir = $this->input->post('stock_akhir');
		$stock_real = $this->input->post('stock_real');
		$stock_sisa = $this->input->post('stock_sisa');

		$this->db->trans_start();

		for($i = 0; $i < count($stock_awal); $i++) {

			$dataAwal[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_awal[$i]
							);

			$dataIn[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_in[$i]
							);

			$dataOut[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_out[$i]
							);

			$dataMove[] = array(
								'item_id'	=> $item_id[$i],
								'qty'		=> $stock_move[$i]
							);

			$dataHilang[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_hilang[$i]
							);

			$dataRusak[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_rusak[$i]
							);

			$dataAkhir[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_akhir[$i]
							);

			$dataReal[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_real[$i]
							);

			$dataSisa[] = array(
								'item_id' 	=> $item_id[$i],
								'qty'		=> $stock_sisa[$i]
							);

		}

		if ($count_data == 0) {

			$data = array(
							'dept_id' 		=> $this->session->userdata('dept_id'),
							'warehouse_id'  => $this->session->userdata('p_warehouse_id'),
							'stock_date'	=> $date,
							'stock_awal'	=> json_encode($dataAwal),
							'stock_in' 		=> json_encode($dataIn),
							'stock_out' 	=> json_encode($dataOut),
							'stock_move'	=> json_encode($dataMove),
							'stock_hilang' 	=> json_encode($dataHilang),
							'stock_rusak' 	=> json_encode($dataRusak),
							'stock_akhir' 	=> json_encode($dataAkhir),
							'stock_real' 	=> json_encode($dataReal),
							'stock_sisa'	=> json_encode($dataSisa),
							'created_time' 	=> date('Y-m-d H:i:s'),
							'updated_time' 	=> date('Y-m-d H:i:s'),
							'creator' 		=> $this->session->userdata('user_id'),
							'updated_by' 	=> $this->session->userdata('user_id')
						);

			$this->db->insert('stock', $data);

		} else {

			$data = array(
							'stock_awal'	=> json_encode($dataAwal),
							'stock_in' 		=> json_encode($dataIn),
							'stock_out' 	=> json_encode($dataOut),
							'stock_move'	=> json_encode($dataMove),
							'stock_hilang' 	=> json_encode($dataHilang),
							'stock_rusak' 	=> json_encode($dataRusak),
							'stock_akhir' 	=> json_encode($dataAkhir),
							'stock_real' 	=> json_encode($dataReal),
							'stock_sisa'	=> json_encode($dataSisa),
							'updated_time' 	=> date('Y-m-d H:i:s'),
							'updated_by' 	=> $this->session->userdata('user_id')
						);		

			$array_where = array(
								'dept_id' => $this->session->userdata('dept_id'),
								'warehouse_id' => $this->session->userdata('p_warehouse_id'),
								'stock_date' => $date
							);

			$this->db->where($array_where);
			$this->db->update('stock', $data);

		}

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}
		
	}
	// End of function save_data

}

/* End of file Update_stock.php */
/* Location: ./application/controllers/Update_stock.php */
