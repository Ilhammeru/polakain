<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Sale_price
 * Sale_price as 'Harga Jual'
 */
class Sale_price extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();

	public function submit_data() {

		// Permission
		$this->session_lib->check_two_permission('or', 'p_sale_price_report', 'p_sale_price_edit');

		// Log
		$this->log_activity_lib->activity_record('Form Harga Jual', 'Visit');

		$attr = array(
						'filterCategory' 	=> $this->display_filter_category(),
						'filterMerk' 		=> $this->display_filter_merk()
					);

		$this->layout_lib->default_template('master/sale_price/submit-data', $attr);
		
	}
	// End of function submit_data

	/**
	 * @return string
	 */
	public function display_filter_merk() {

		// Get merk by dept
		$item = $this->db->query("SELECT DISTINCT merk
										 FROM item
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 AND (merk IS NOT NULL
										 OR merk != '')
										 ORDER BY merk ASC")->result();

		$html = '<select class="form-control select2" multiple><option disabled>Cari Merk</option>';

		foreach ($item as $row) :

			if ($row->merk != '') {

			$html .= '<option>' . $row->merk . '</option>';

			}

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_merk

	/**
	 * @return string
	 */
	public function display_filter_category() {

		// Get category by dept
		$item = $this->db->query("SELECT DISTINCT category
										 FROM item
										 WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
										 AND (category IS NOT NULL
										 OR category != '')
										 ORDER BY category ASC")->result();

		$html = '<select class="form-control select2" multiple><option disabled>Cari Kategori</option>';

		foreach ($item as $row) :

			if ($row->category != '') {

			$html .= '<option>' . $row->category . '</option>';

			}

		endforeach;

		$html .= '</select>';

		return $html;
		
	}
	// End of function display_filter_category

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function server_side_data() {

		// Set field order column
		$columnOrder = array('name', 'code', 'category', 'merk', '', '');

		// Set field search column
	    $columnSearch = array(
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'item.name',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'item.code',
    							'type' 		=> 'search'
    						),
    						array(
    							'format' 	=>  'string',
    							'field' 	=> 'item.category',
    							'type' 		=> 'select-multiple'
    						)
	    				);
    	
    	// Set field ordering
    	$order = array('item.name' => 'asc');

    	$countTotal = "SELECT id
    						  FROM item
    						  WHERE dept_id = " . $this->session->userdata('dept_id');

    	$countTotal = $this->db->query($countTotal)->num_rows();

    	// Query
    	$query = "SELECT id,
    					 name,
    					 code,
    					 category,
    					 merk
    					 FROM item ";

   		$query .= $this->server_side_lib->individual_column_filtering($columnSearch, 'item');

   		$query .= $this->server_side_lib->ordering($columnOrder, $order);

   		//$item = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($query)->result();

   		// No condition
   		$resultsSalePrice = $this->db->query("SELECT detail 
   													 FROM sale_price 
   													 WHERE dept_id = " . $this->session->userdata('dept_id') . "
   													 LIMIT 1")->result_array();

   		if (count($resultsSalePrice) > 0) {
   			$salePrice = json_decode($resultsSalePrice[0]['detail'], TRUE);
   		} else {
   			$salePrice = array();
   		}

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$key = 'i' . $rows->id;

			$row[] = $rows->name;

			$row[] = $rows->code;

			$row[] = $rows->category;

			if (isset($salePrice[$key])) {

				$row[] = '<div class="text-right" id="text-' . $rows->id . '">' . number_format($salePrice[$key], 2, '.', ',') . '</div>';

				$value = $salePrice[$key];

			} else {

				$row[] = '<div class="text-right" id="text-' . $rows->id . '"></div>';

				$value = 0;

			}

			if ($this->session->userdata('p_sale_price_edit') == 1) {

				$row[] = '<input type="text" class="form-control currency-rp" id="i' . $rows->id . '" onblur="updateSalePrice(' . $rows->id . ')" value="' . $value . '">';

			} else {
				$row[] = '';
			}

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
	 * @param post => code
	 */
	public function get_sale_price_history() {
		
		$item = $this->db->query("SELECT name, price_history 
										 FROM item 
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 AND code = '" . $this->input->post('code') . "'")->result_array();

		if ($item[0]['price_history'] != "") {

			$priceHistory = json_decode($item[0]['price_history'], TRUE);

			$list = '<table class="table table-striped table-valign-middle m-0">';

			$list .= '<tr><th colspan="3" style="text-align:center">' . $item[0]['name'] . '</th></tr>';

			// sort desc key of array priceHistory
			krsort($priceHistory);

			foreach ($priceHistory as $key => $arr) {

				$list .= '<tr>';

				$list .= '<td>' . date_format(date_create($key), 'd M Y H:i:s') . '</td>';

				$list .= '<td>' . number_format($arr['price'], 2, '.', ',') . '</td>';

				$list .= '<td>' . $arr['username'] . '</td>';

				$list .= '</tr>';

			}

			$list .= '</table>';

			echo $list;

		} else {
			echo '<div class="p-4">Tidak terdapat history harga</div>';
		}

	}
	// End of function get_sale_price_history

	/**
	 * @param post => id
	 * @param post => new_price
	 */
	public function update_sale_price() {

		// is ! empty post
		if (! empty($this->input->post('id')) || ! empty($this->input->post('new_price'))) {

			$key = 'i' . $this->input->post('id');

			$newPrice = str_replace(",","",str_replace(".00", "", $this->input->post('new_price')));

			// No condition
	   		$resultsSalePrice = $this->db->query("SELECT detail 
	   													 FROM sale_price 
	   													 WHERE dept_id = " . $this->session->userdata('dept_id') . "
	   													 LIMIT 1")->result_array();

	   		if (count($resultsSalePrice) > 0) {
	   			$salePrice = json_decode($resultsSalePrice[0]['detail'], TRUE);

		   		if (isset($salePrice[$key])) {

		   			// update
		   			$this->db->query("UPDATE sale_price 
		   									 SET detail = JSON_REPLACE(detail, '$." . $key . "', $newPrice)
		   									 WHERE dept_id = " . $this->session->userdata('dept_id'));

		   		} else {

		   			// insert
		   			$this->db->query("UPDATE sale_price 
		   									 SET detail = JSON_SET(detail, '$." . $key . "', $newPrice)
		   									 WHERE dept_id = " . $this->session->userdata('dept_id'));

		   		}
	   		} else {

	   			$data = array(
	   							'dept_id' 		=> $this->session->userdata('dept_id'),
	   							'detail' 		=> '{"' . $key . '": ' . $newPrice . '}',
	   							'created_time' 	=> date('Y-m-d H:i:s'),
	   							'updated_time' 	=> date('Y-m-d H:i:s'),
	   							'creator' 		=> $this->session->userdata('user_id'),
	   							'updated_by' 	=> $this->session->userdata('user_id')
	   						);

	   			$this->db->insert('sale_price', $data);

	   		}

	   		// get item
	   		$item = $this->db->query("SELECT name, 
	   										 price_history 
	   										 FROM item 
	   										 WHERE id = " . $this->input->post('id'))->row_array();
		   	
		   	$getDate = date('Y-m-d H:i:s');
		   	$username = $this->session->userdata('username');

		   	// is price_history null
	   		if ($item['price_history'] == "") {

	   			// insert history if null
	   			$priceHistory = '{"' . $getDate . '": {"username": "' . $username . '", "price": ' . $newPrice . '}}';
	   			$this->db->query("UPDATE item SET price_history = '$priceHistory' WHERE id = " . $this->input->post('id'));

	   		} else {

		   		// insert history if isset
		   		$this->db->query("UPDATE item 
		   						  SET price_history = JSON_SET(price_history, '$." . $getDate . "', 
		   						  						JSON_OBJECT('username', '" . $username . "', 'price', " . $newPrice . ")) 
		   						  WHERE id = " . $this->input->post('id'));

		   	}

		   	$remarks = $item['name'] . ': ' . $newPrice;

		   	// Log
		   	$this->log_activity_lib->activity_record('Harga Jual', 'Edit', 'sale_price', $this->input->post('id'), $remarks);

	   		echo 'success';

	   	} else {

	   		echo 'error';

	   	}
	   	// End of empty post id
		
	}
	// End of function update_sale_price


}
/* End of file Sale_price.php */
/* Location: ./application/controllers/Sale_price.php */
