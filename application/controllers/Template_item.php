<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Template_item
 */
class Template_item extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();

	public function submit_data() {

		// Permission
		$this->session_lib->check_permission('p_template_item');

		// Log
		$this->log_activity_lib->activity_record('Form Template', 'Visit');

		if ($this->input->get('brand_id') != '') {
			$template = $this->db->query("SELECT id,
												 brand, 
												 tipe 
												 FROM template_item 
												 WHERE id = " . $this->input->get('brand_id'))->row_array();
		} else {
			$template = "";
		}

		$attr = array(
						'brand_id' 	=> $this->input->get('brand_id'),
						'template' 	=> $template,
						'brand_count'=> $this->db->query("SELECT id, brand, tipe 
														    	FROM template_item 
														    	WHERE dept_id = " . $this->session->userdata('dept_id') . "
														    	ORDER BY brand ASC")->num_rows()
					);

		$this->layout_lib->default_template('master/template_item/submit-data', $attr);
		
	}
	// End of function submit_data

	##################################################################################################################################
	#                                                              API                                                               #
	##################################################################################################################################

	public function load_modal_add_brand() {
		$attr = array(
						'brand' => $this->db->query("SELECT DISTINCT brand 
															FROM template_item 
															WHERE dept_id = " . $this->session->userdata('dept_id') . "
															ORDER BY brand ASC")->result()
					);
		$this->load->view('master/template_item/modal-add-brand', $attr);		
	}
	// End of function load_modal_add_brand

	public function load_brand() {

		if ($this->input->get('brand')) {
			$brand = $this->input->get('brand');
		} else {
			$brand = "";
		}

		$data = array(
					'brand' => $this->db->query("SELECT id, brand, tipe 
													    FROM template_item 
													    WHERE dept_id = " . $this->session->userdata('dept_id') . "
													    AND brand LIKE '%" . $brand . "%'
													    ORDER BY brand ASC")->result()
				);

		if ($data['brand'] != null) {

			echo json_encode($data);

		} else {

			echo 'error-null';

		}

	}
	// End of function load_brand

	public function load_item() {

		if ($this->input->get('brand_id') == '') {
			$template = "";
		} else {
			$query = $this->db->query("SELECT detail
											  FROM template_item
										      WHERE id = " . $this->input->get('brand_id'))->result_array();

			$template = json_decode($query[0]['detail'], TRUE);
		}

		$data = array(
						'item' 		=> $this->db->query("SELECT id, name 
													       FROM item
													       WHERE dept_id = " . $this->session->userdata('dept_id') . "
													       ORDER BY name ASC")->result(),
						'template' 	=> $template
					);

		echo json_encode($data);
		
	}
	// End of function load_item

	/**
	 * @param get => brand_id
	 */
	public function load_template() {

		// is get null
		if ($this->input->get('brand_id') == "") {

			echo 'error-null';

		} else {

			$result = $this->db->query("SELECT detail 
											   FROM template_item 
											   WHERE id = " . $this->input->get('brand_id'))->result_array();

			if ($result[0]['detail'] != "") {

				$item = $this->db->query("SELECT id, name 
											     FROM item 
											     WHERE dept_id = " . $this->session->userdata('dept_id') . "
												 ORDER BY name ASC")->result();
												 
				$salePrice = $this->db->query("SELECT detail
													  FROM sale_price
													  WHERE dept_id = " . $this->session->userdata('dept_id') . "
													  ORDER BY updated_time DESC LIMIT 1")->row_array();

				$detailPrice = json_decode($salePrice['detail'], TRUE);

				$jsonData = json_decode($result[0]['detail'], TRUE);

				$results = '<table class="table table-valign-middle m-0">';

				$total = 0;
				foreach ($item as $row) {

					$key = 'i' . $row->id;

					if (isset($jsonData[$key])) {

						if (isset($detailPrice[$key])) {
							$itemPrice = $detailPrice[$key];
							$subtotalPrice = $itemPrice * $jsonData[$key];
						} else {
							$itemPrice = 0;
							$subtotalPrice = 0;
						}

						$results .= '<tr>
										<td style="width:40%">' . $row->name . '</td>
										<td style="width:15%" class="text-center" id="price-' . $row->id . '">' . number_format($itemPrice) . '</td>
										<td style="width:15%">
											<input type="number" id="' . $row->id . '" class="form-control" value="' . $jsonData[$key] . '" onchange="insert_qty(' . $row->id . ')">
										</td>
										<td style="width:20%" class="text-center subtotal" id="subtotal-' . $row->id . '">' . number_format($subtotalPrice) . '</td>
										<td style="width:10%">
											<a href="' . site_url('template_item/delete_item?item_id=' . $row->id . '&brand_id=' . $this->input->get('brand_id')) . '"><i class="fa fa-angle-double-right text-danger"></i></a>
										</td>
									</tr>';
						
						$total += $subtotalPrice;

					}
				}

				$results .= '</table>';

				$results .= '</div>';
				$results .= '<div class="card-footer text-right"><span id="total">' . number_format($total) . '</span>';

				echo $results;

			} else {
				echo 'null';
			}

		}

	}
	// End of function load_template

	/**
	 * @param post => brand_name
	 * @param post => brand_category
	 */
	public function save_add_brand() {

		// is post null
		if ($this->input->post('brand_name') == '' ||
			$this->input->post('brand_category') == '') {

			$response = array(
								'status' => 'error-null'
							);

			echo $response;

		} else {

			$checkDuplicate = $this->db->query("SELECT id 
													   FROM template_item 
											    	   WHERE dept_id = " . $this->session->userdata('dept_id') . "
											    	   AND brand = '" . $this->input->post('brand_name') . "'
											    	   AND tipe = '" . $this->input->post('brand_category') . "'")->num_rows();

			// is duplicate
			if ($checkDuplicate > 0) {

				$this->output->set_content_type('application/json')->set_output(json_encode(array(
        																				'status' => 'error-duplicate',
        																				'brand_id' => 0
        																			)
    																			));

			} else {
				
				$data = array(
								'dept_id' 		=> $this->session->userdata('dept_id'),
								'brand' 		=> ucwords($this->input->post('brand_name')),
								'tipe' 			=> ucwords($this->input->post('brand_category')),
								'created_time' 	=> date('Y-m-d H:i:s'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'creator' 		=> $this->session->userdata('user_id'),
								'updated_by' 	=> $this->session->userdata('user_id')
							);		

				$this->db->insert('template_item', $data);

				$brandId = $this->db->insert_id();

				$remarks = $this->input->post('brand_name') . '-' . $this->input->post('brand_category');
				// Log
				$this->log_activity_lib->activity_record('Template', 'Add', 'template_item', $this->db->insert_id(), $remarks);

				$this->output->set_content_type('application/json')->set_output(json_encode(array(
			        																				'status' 	=> 'success',
			        																				'brand_id' 	=> $brandId
			        																			)
			    																			));

			}
			// End of is duplicate

		}
		// End of is post null

	}
	// End of function save_add_brand

	/**
	 * @param get => item_id
	 * @param get => brand_id
	 */
	public function add_item_to_template() {
		
		$template 	= $this->db->query("SELECT detail 
											   FROM template_item 
											   WHERE id = " . $this->input->get('brand_id'))->row_array();

		$key 		= 'i' . $this->input->get('item_id');

		if ($template['detail'] == '') {

			$detail = '{"' . $key . '": 1}';
			$this->db->query("UPDATE template_item SET detail = '" . $detail . "' WHERE id = " . $this->input->get('brand_id'));

		} else {

			$this->db->query("UPDATE template_item SET detail = JSON_SET(detail, '$." . $key . "', 1) WHERE id = " . $this->input->get('brand_id'));

		}

		redirect('template_item/submit_data?brand_id=' . $this->input->get('brand_id'));

	}
	// End of function add_item_to_template

	/**
	 * @param get => item_id
	 * @param get => qty
	 */
	public function insert_qty() {

		$item_id = $this->input->get('item_id');
		$qty = $this->input->get('qty');
		$brandId = $this->input->get('brand_id');

		$this->db->query("UPDATE template_item SET detail = JSON_REPLACE(detail, '$.i" . $item_id . "', '" . $qty . "') WHERE id = " . $brandId);

		echo 'success';
		
	}
	// End of function insert_qty

	/**
	 * @param get => item_id
	 * @param get => brand_id
	 */
	public function delete_item() {
		
		$item_id = $this->input->get('item_id');
		$brand_id = $this->input->get('brand_id');

		$this->db->query("UPDATE template_item SET detail = JSON_REMOVE(detail, '$.i" . $item_id . "') WHERE id = " . $brand_id);

		redirect('template_item/submit_data?brand_id=' . $brand_id);

	}
	// End of function delete_item

	/**
	 * @param post => id
	 */
	function delete_template() {

		// Permission

		$this->db->trans_start();

		$this->db->where('id', $this->input->post('id'));

		$this->db->delete('template_item');

		// Log

		if ($this->db->trans_status() === false) {

				$this->db->trans_rollback();

				echo 'error';

			} else {

				$this->db->trans_commit();

				echo 'success';

			}

	}
	// End of function delete_template

	public function print_template_list() {

		$department = $this->db->query("SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT . ', '')), REPLACE(name, 'PT . ', '')) AS 'name'
											   FROM ansena_department
											   WHERE id = " . $this->session->userdata('dept_id'))->row_array();

		$template = $this->db->query("SELECT id, CONCAT(brand, ' - ', tipe) AS 'brand', detail
											 FROM template_item
											 WHERE dept_id = " . $this->session->userdata('dept_id') . "
											 ORDER BY brand, tipe ASC")->result();

		$item = $this->db->query("SELECT id, name 
										 FROM item 
										 WHERE dept_id = " . $this->session->userdata('dept_id') . "
										 ORDER BY name ASC")->result();

		$arrayItem = array();
		foreach ($item as $row) :
			$arrayItem['i' . $row->id] = $row->name;
		endforeach;
												 
		$salePrice = $this->db->query("SELECT detail
											  FROM sale_price
											  WHERE dept_id = " . $this->session->userdata('dept_id') . "
											  ORDER BY updated_time DESC LIMIT 1")->row_array();

		$detailPrice = json_decode($salePrice['detail'], TRUE);

		$results = '<table><tr><td colspan="2"><h2>PAKET ' . $department['name'] . '</h2></td></tr>';

		foreach ($template as $row) :

			$total = 0;

			$results .= '<tr>';
			$results .= '<td class="border">' . $row->brand . '</td>';
			//$results .= '</tr>';

			$detail = json_decode($row->detail, TRUE);

			foreach ($detail as $key => $x) :

				$price = 0;
				$subtotal = 0;

				if (isset($arrayItem[$key])) {
					$itemName = $arrayItem[$key];
				}

				if (isset($detailPrice[$key])) {
					$price = $detailPrice[$key];
					$subtotal = $price * $x;
					$total += $subtotal;
				}
				
				// $results .= '<tr class="border">';
				// $results .= '<td class="border" style="width: 50%">' . $itemName . '</td>';
				// $results .= '<td class="border text-center" style="width: 15%">' . number_format($price) . '</td>';
				// $results .= '<td class="border text-center" style="width: 15%">' . $x . '</td>';
				// $results .= '<td class="border text-right pr-10" style="width: 20%">' . number_format($subtotal) . '</td>';
				// $results .= '</tr>';

			endforeach;

			//$results .= '<tr>';
			$results .= '<td class="border text-right pr-10">' . number_format($total) . '</td>';
			$results .= '</tr>';

		endforeach;

		$results .= '</table>';

		$attr = array(
						'table' => $results
					);

		$this->load->view('master/template_item/print', $attr);

	}
	// End of function print_template_list

}
/* End of file Template_item.php */
/* Location: ./application/controllers/Template_item.php */

