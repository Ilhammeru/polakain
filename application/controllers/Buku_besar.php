<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Buku_besar
 */
class Buku_besar extends CI_Controller {

	private $closingDate;

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

		$this->closingDate = lock_closing('Buku Besar');

	}
	// End of function __construct();

	public function index() {

		// Permission
		$this->session_lib->check_permission('p_buku_besar_report');

		$date = $this->input->get('date');

		$warehouse_count = $this->db->query("SELECT id FROM warehouse 
													   WHERE dept_id = " .  $this->session->userdata('dept_id'))->num_rows();

		$stock_count = $this->db->query("SELECT id
										  		FROM stock
										  		WHERE dept_id = " . $this->session->userdata('dept_id') . "
										  		AND stock_date = DATE_FORMAT('" . $date . "', '%Y-%m-%d')")->num_rows();

		$closing = 0;
		if ($warehouse_count == $stock_count) {
			$closing = 1;
		}

		$attr = array(
						'filterCategory' => $this->db->query("SELECT DISTINCT category
																	 FROM item
																	 WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
																	 AND (category IS NOT NULL
																	 OR category != '')
																	 ORDER BY category ASC")->result(),
						'date'			=> $this->input->get('date'),
						'stock_count'	=> $stock_count,
						'closing'		=> $closing,
						'closingDate'	=> $this->closingDate
					);

		$this->layout_lib->template_with_custom_navbar('transaction/buku-besar/navbar-buku-besar', 'transaction/buku-besar/index', $attr);
		
	}
	// End of function index

	public function preview_data() {

		$category = $this->input->get('category');

		$date = $this->input->get('date');

		$stock_count = $this->input->get('stock_count');

		$date_yesterday = date('Y-m-d', strtotime('-1 days', strtotime($date)));

		$data = array();

		$count_data = $this->db->query("SELECT detail
										  	   FROM buku_besar
										  	   WHERE dept_id = " . $this->session->userdata('dept_id') . "
										  	   AND category = '" . $category . "'
										  	   AND DATE_FORMAT(date_buku_besar, '%Y-%m-%d') = '" . $date . "'");

		$count_data = $count_data->num_rows();

		$item = $this->db->query("SELECT id,
										 name
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 AND category = '" . $category . "'
										 ORDER BY name ASC")->result();

		$resultsSalePrice = $this->db->query("SELECT detail 
   													 FROM sale_price 
   													 WHERE dept_id = " . $this->session->userdata('dept_id') . "
   													 LIMIT 1")->result_array();

   		if (count($resultsSalePrice) > 0) {
   			$salePrice = json_decode($resultsSalePrice[0]['detail'], TRUE);
   		} else {
   			$salePrice = array();
   		}

		$basic = $this->db->query("SELECT detail
										  FROM buku_besar
										  WHERE dept_id = " . $this->session->userdata('dept_id') . "
										  AND category = '" . $category . "'
										  AND DATE_FORMAT(date_buku_besar, '%Y-%m-%d') = '" . $date_yesterday . "'");

		$invoice = $this->db->query("SELECT date_invoice, 
										    item_id, 
										    SUM(qty) AS 'qty',
										    price,
											invoice_detail.total_price,
											disc,
											if(disc != 0, price - (disc/SUM(qty)), price) AS 'afterdisc'
										    FROM invoice 
										    JOIN invoice_detail ON invoice_detail.invoice_id = invoice.id 
										    JOIN item ON item.id = invoice_detail.item_id
										    WHERE invoice.dept_id = " . $this->session->userdata('dept_id') . "
										    AND DATE_FORMAT(date_invoice, '%Y-%m-%d') = '" . $date . "' 
										    AND item.category = '" . $category . "'
										    GROUP BY invoice.id, item_id
										    ORDER BY item_id, date_invoice ASC")->result();

		$sales = $this->db->query("SELECT detail,
										  created_time
										  FROM sale_detail
										  JOIN sale ON sale.id = sale_detail.sale_id
										  WHERE sale.dept_id = " . $this->session->userdata('dept_id') . "
										  AND DATE_FORMAT(sale.date_kirim, '%Y-%m-%d') = '" . $date . "'")->result();

		// $move = $this->db->query("SELECT detail,
		// 								 created_time
		// 				 				 FROM move_item
		// 				 				 WHERE dept_id = " . $this->session->userdata('dept_id') . "
		// 				 				 AND from_warehouse =  " . $this->session->userdata('p_warehouse_id') . "
		// 				 				 AND DATE_FORMAT(created_time, '%Y-%m-%d') = '" . $this->input->get('date') . "'")->result();

		$rusak_hilang_sisa = $this->db->query("SELECT stock_rusak, stock_hilang, stock_sisa, stock_move
						 				  		 	  FROM stock
						 				  		 	  WHERE dept_id = " . $this->session->userdata('dept_id') . "
														  AND DATE_FORMAT(stock_date, '%Y-%m-%d') = '" . $date . "'")->result();
														  
		$penjualanLunas = $this->db->query("SELECT SUM(total_price) AS 'total'
											  	   FROM sale
											  	   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   AND DATE_FORMAT(date_kirim, '%Y-%m-%d') = '" . $date . "'
												   AND date_lunas IS NOT NULL
												   GROUP BY DATE_FORMAT(date_kirim, '%Y-%m-%d')");
												   
		$penjualanPiutang = $this->db->query("SELECT SUM(total_price) AS 'total'
											  	   FROM sale
											  	   WHERE dept_id = " . $this->session->userdata('dept_id') . "
												   AND DATE_FORMAT(date_kirim, '%Y-%m-%d') = '" . $date . "'
												   AND date_lunas IS NULL
											       GROUP BY DATE_FORMAT(date_kirim, '%Y-%m-%d')");

		if ($penjualanLunas->num_rows() > 0) {
			$penjualanLunas = $penjualanLunas->row_array();
			$penjualanLunas = $penjualanLunas['total'];
		} else {
			$penjualanLunas = 0;
		}

		if ($penjualanPiutang->num_rows() > 0) {
			$penjualanPiutang = $penjualanPiutang->row_array();
			$penjualanPiutang = $penjualanPiutang['total'];
		} else {
			$penjualanPiutang = 0;
		}

		foreach ($item as $row) :

			$arrayItem['i' . $row->id] = $row->id;

		endforeach;

		if ($basic->num_rows() > 0) {

			$basic = $basic->row_array();

			if ($basic['detail'] != 'null') {

				$detail = json_decode($basic['detail'], TRUE);

				foreach ($detail as $key => $row) :

					$countRow = count($detail[$key]) -1;

					$data[] = array(
									'item_id'		=> preg_replace("/[^0-9]/", "", $key),
									'created'		=> date_format(date_create($date), 'd M Y'),
									'type'			=> 'Stock Awal',
									'qty'			=> intval($row[$countRow]['rqty']),
									'price'			=> floatval($row[$countRow]['rprice']),
									'total_price' 	=> floatval($row[$countRow]['rtotal_price'])
							);

				endforeach;

				$basic = 1;
			} else {
				$basic = 0;
			}
		} else {
			$basic = 0;
		}
		
		foreach ($invoice as $row) :

			if ($row->disc != '0.00') {
				$price = floatval($row->afterdisc);
				$total_price = $price * $row->qty;
			} else {
				$price = $row->total_price/$row->qty;
				$total_price = $row->total_price;
			}

			$data[] = array(
								'item_id'		=> $row->item_id,
								'created'		=> date_format(date_create($row->date_invoice), 'd M Y H:i:s'),
								'type'			=> 'Pembelian',
								'qty'			=> intval($row->qty),
								'price'			=> $price,
								'total_price' 	=> $total_price
						);

		endforeach;

		foreach ($sales as $row) :

			//[{"template_id":"0","item_id":"5","qty":"1","price":"99999","sale_price_id":"2"}]

			foreach (json_decode($row->detail) as $x) {

				if (isset($arrayItem['i' . $x->item_id])) {

					$data[] = array(
									'item_id'		=> $x->item_id,
									'created'		=> date_format(date_create($row->created_time), 'd M Y H:i:s'),
									'type'			=> 'Penjualan',
									'qty'			=> intval($x->qty),
									'price'			=> floatval($x->price),
									'total_price' 	=> intval($x->qty) * floatval($x->price)
								);

				}

			}

		endforeach;

		// foreach ($move as $row) :

		// 	//[{"template_id":"0","item_id":"5","qty":"1","price":"99999","sale_price_id":"2"}]

		// 	foreach (json_decode($row->detail) as $x) {

		// 		if (isset($arrayItem['i' . $x->item_id])) {

		// 			$data[] = array(
		// 							'item_id'		=> $x->item_id,
		// 							'created'		=> date_format(date_create($row->created_time), 'd M Y H:i:s'),
		// 							'type'			=> 'Pindah',
		// 							'qty'			=> intval($x->qty),
		// 							'price'			=> 0,
		// 							'total_price' 	=> 0
		// 						);

		// 		}

		// 	}

		// endforeach;

		foreach ($rusak_hilang_sisa as $row) :

			$rusak = json_decode($row->stock_rusak);
			$hilang = json_decode($row->stock_hilang);
			$sisa = json_decode($row->stock_sisa);

			foreach ($rusak as $x) {

				if ($x->qty != 0) {

					$data[] = array(
									'item_id'		=> $x->item_id,
									'created'		=> date_format(date_create($date), 'd M Y 23:59:59'),
									'type'			=> 'Kerusakan',
									'qty'			=> $x->qty,
									'price'			=> 0,
									'total_price' 	=> 0
							);

				}

			}

			foreach ($hilang as $x) {

				if ($x->qty != 0) {

					$data[] = array(
									'item_id'		=> $x->item_id,
									'created'		=> date_format(date_create($date), 'd M Y 23:59:59'),
									'type'			=> 'Kehilangan',
									'qty'			=> $x->qty,
									'price'			=> 0,
									'total_price' 	=> 0
							);

				}

			}

			foreach ($sisa as $x) {

				if ($x->qty != 0) {

					$data[] = array(
									'item_id'		=> $x->item_id,
									'created'		=> date_format(date_create($date), 'd M Y 23:59:59'),
									'type'			=> 'Kelebihan',
									'qty'			=> $x->qty,
									'price'			=> 0,
									'total_price' 	=> 0
							);

				}

			}

		endforeach;

		$array_column = array_column($data, 'item_id');

		array_multisort($array_column, SORT_ASC, $data);

		$attr = array(
						'stock_count' 	=> $stock_count,
						'count_data'	=> $count_data,
						'category' 		=> $category,
						'date'			=> $date,
						'item'			=> $item,
						'data' 			=> $data,
						'basic'			=> $basic,
						'salePrice'		=> $salePrice,
						'date'			=> $date,
						'closingDate'	=> $this->closingDate,
						'penjualanLunas'=> $penjualanLunas,
						'penjualanPiutang'=> $penjualanPiutang
					);

		$this->load->view('transaction/buku-besar/preview-data', $attr);

	}
	// End of function preview_data

	public function save_data() {

		// Permission
		$this->session_lib->check_permission('p_buku_besar_approval');

		$this->db->trans_start();

		$category 	= $this->input->post('category');
		$date 		= $this->input->post('date');
		$count_data = $this->input->post('count_data');

		$query = $this->db->query("SELECT id
										FROM buku_besar
										WHERE dept_id = " . $this->session->userdata('dept_id') . "
										AND category = '" . $category . "'
										AND date_buku_besar = '" . $date . "'");

		$item = $this->db->query("SELECT id,
										 name
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 AND category = '" . $category . "'
										 ORDER BY name ASC")->result();

		foreach ($item as $row) :

			$key = 'i' . $row->id;
			$post = $this->input->post($key);

			if ($post !='') {

				for ($i = 0; $i < count($post); $i++) {

					$explode = explode(',x,', $post[$i]);

					$detail[$key][] = array(
									'type' 			=> $explode[0],
									'qty'			=> $explode[1],
									'price'			=> $explode[2],
									'total_price' 	=> $explode[3],
									'rqty'			=> $explode[5],
									'rprice'		=> $explode[6],
									'rtotal_price'	=> $explode[7]
								);

				}

			}

		endforeach;

		if ($query->num_rows() > 0) {

			$data = array(
						'detail'		=> json_encode($detail),
						'updated_time' 	=> date('Y-m-d H:i:s'),
						'updated_by'	=> $this->session->userdata('user_id'),
					);

			$array_where = array(
									'dept_id'		  => $this->session->userdata('dept_id'),
									'date_buku_besar' => $date,
									'category'		  => $category
								);

			$this->db->where($array_where);
			$this->db->update('buku_besar', $data);

		} else {

			$data = array(
						'dept_id' 		=> $this->session->userdata('dept_id'),
						'date_buku_besar'=> $date,
						'category'		=> $category,
						'detail'		=> json_encode($detail),
						'created_time' 	=> date('Y-m-d H:i:s'),
						'updated_time' 	=> date('Y-m-d H:i:s'),
						'creator'		=> $this->session->userdata('user_id'),
						'updated_by'	=> $this->session->userdata('user_id'),
					);

			$this->db->insert('buku_besar', $data);

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

/* End of file Buku_besar.php */
/* Location: ./application/controllers/Buku_besar.php */

