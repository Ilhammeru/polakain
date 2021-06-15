<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

//load barcode library 
require('./vendor/autoload.php');

/*
 * Class Item
 * Item as 'Barang'
 */
class Item extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct();
	
	public function barcode($code) {
		$color = [0,0,0];

		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		file_put_contents('./assets/barcode/' . $code . '.png' , $generator->getBarcode($code, $generator::TYPE_CODE_128, 1, 50, $color));
	}
	// end of function barcode

	public function report_data() {

		// Permission
		$this->session_lib->check_permission('p_item_report');

		// Log
		$this->log_activity_lib->activity_record('Barang', 'Visit');

		$attr = array(
						'filterCategory' 	=> $this->display_filter_category()
					);

		$this->layout_lib->default_template('master/item/report-data', $attr);
		
	}
	// End of function report_data

	public function form_new_item() {

		// Permission
		$this->session_lib->check_permission('p_item_add');

		// Log
		$this->log_activity_lib->activity_record('Form Barang', 'Visit');

		$attr = array(
						'category' => $this->db->query("SELECT id, name AS category
															   FROM itemcategory
														       WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
															   ORDER BY category ASC")->result(),
						'itemname' => $this->db->query("SELECT id,  name
															   FROM itemname
															   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
															   ORDER BY name ASC")->result(),
						'color' => $this->db->query("SELECT id, name AS color
																FROM itemcolor
															    ORDER BY color ASC")->result()
					);

		$this->layout_lib->default_template('master/item/form-new-item', $attr);
		
	}
	// End of function form_new_item

	/**
	 * @param get => $id
	 */
	public function form_edit_item() {

		$id = $this->input->get('item_id');

		// Permission
		$this->session_lib->check_permission('p_item_edit');

		// Log
		$this->log_activity_lib->activity_record('Form Barang', 'Visit');

		$attr = array(	
						'item_id' 	=> $id,
						'category' => $this->db->query("SELECT id, name AS category
															   FROM itemcategory
														       WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
															   ORDER BY category ASC")->result(),
						'itemname' => $this->db->query("SELECT id,  name
															   FROM itemname
															   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
															   ORDER BY name ASC")->result(),
						'color' => $this->db->query("SELECT id, name AS color
																FROM itemcolor
															    ORDER BY color ASC")->result(),
						'item'		=> $this->db->query("SELECT item.id, 
																item.name, 
																JSON_EXTRACT(attribute, '$.name') AS itemname,
																JSON_EXTRACT(attribute, '$.category') AS itemcategory,
																JSON_EXTRACT(attribute, '$.color') AS itemcolor,
																JSON_EXTRACT(attribute, '$.name_id') AS name_id,
																JSON_EXTRACT(attribute, '$.category_id') AS category_id,
																JSON_EXTRACT(attribute, '$.color_id') AS color_id,
																item.updated_time, 
																username
														   		FROM item
														   		JOIN users ON users.id = item.updated_by 
														   		WHERE item.id = '$id'")->row_array()
					);

		$this->layout_lib->default_template('master/item/form-edit-item', $attr);
		
	}
	// End of function form_edit_item

	/**
	 * @return string
	 */
	public function display_filter_merk() {

		// Get merk by dept
		$item = $this->db->query("SELECT DISTINCT merk
										 FROM item
										 WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
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

	// public function create_all_barcode() {

	// 	$item = $this->db->query("SELECT code
	// 									 FROM item
	// 									 WHERE dept_id = '" . $this->session->userdata('dept_id') . "'")->result();

	// 	$this->load->library('ciqrcode'); //pemanggilan library QR CODE

	// 	foreach ($item as $row) :

	// 		$config['cacheable'] = true; //boolean, the default is true
	// 		$config['cachedir'] = './assets/'; //string, the default is application/cache/
	// 		$config['errorlog'] = './assets/'; //string, the default is application/logs/
	// 		$config['imagedir'] = './assets/barcode/'; //direktori penyimpanan qr code
	// 		$config['quality'] = true; //boolean, the default is true
	// 		$config['size'] = '1024'; //interger, the default is 1024
	// 		$config['black'] = array(224,255,255); // array, default is array(255,255,255)
	// 		$config['white'] = array(70,130,180); // array, default is array(0,0,0)
	// 		$this->ciqrcode->initialize($config);

	// 		$image_name = $row->code . '.png'; //buat name dari qr code

	// 		$params['data'] = $row->code; //data yang akan di jadikan QR CODE
	// 		$params['level'] = 'H'; //H=High
	// 		$params['size'] = 10;
	// 		$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/barcode/
	// 		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

	// 	endforeach;

	// }
	// // End of function create_all_barcode

	public function print_barcode() {

		$attr['item'] = $this->db->query("SELECT name, code, category, merk FROM item WHERE code = '" . $this->input->get('code') . "'")->row_array();

		$this->load->view('master/item/print-barcode', $attr);
		
	}
	// End of function print_barcode

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
		$columnOrder = array('name', 'code', 'category', '');

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

   		$item = $query . $this->server_side_lib->limit();

   		$results = $this->db->query($item)->result();

   		// Loop
		$data = array();

		foreach ($results as $rows):

			$row = array();

			$row[] = $rows->name;

			$row[] = $rows->code;
			//$row[] = '<img src="' . base_url() . 'assets/barcode/' . $rows->code . '.png">';

			$row[] = $rows->category;

			if ($this->session->userdata('p_item_edit') == 1) {

				$btnEdit = '<a href="' . site_url('item/form_edit_item?item_id=' . $rows->id) . '" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></a>';
			} else {

				$btnEdit = '';

			}

			if ($this->session->userdata('p_item_delete') == 1) {

				$btnDelete = '<a href="javascript:void(0)" id="btn-confirm-delete" key="' . $rows->id . '" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>';

			} else {

				$btnDelete = '';

			}

			$btnViewBarcode = '<a href="' . site_url('item/print_barcode') . '?code=' . $rows->code . '" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="fa fa-qrcode"></i></a>';

			$btnPrintBarcode = '<a onclick="view_print('. $rows->id .')" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="fa fa-print"></i></a>';

			$row[] = '<div class="btn-group">' . $btnViewBarcode . $btnPrintBarcode . $btnEdit . $btnDelete . '</div>';

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
	 * @return json
	 */
	public function load_item_list() {

		if (empty($this->input->get('item'))) {

			$data['result'] = $this->db->query("SELECT id, name 
													   FROM item
													   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
													   ORDER BY name ASC")->result();

		} else {

			$data['result'] = $this->db->query("SELECT id, name 
													   FROM item
													   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
													   AND name LIKE '%" . $this->input->get('item') . "%'
													   ORDER BY name ASC")->result();

		}

		echo json_encode($data);
		
	}
	// End of function load_item_list

	/**
	 * @param $id
	 */
	public function load_modal_confirm_delete($id) {

		$attr = array('id' => $id);
		$this->load->view('master/item/modal-confirm-delete', $attr);
		
	}
	// End of function load_modal_confirm_delete

	/**
	 * @param post => category_id
	 * @param post => color
	 */
	public function save_item() {

		$category_id 	= $this->input->post('category_id');
		$color  		= $this->input->post('color');

		$query = $this->db->query("SELECT name
										FROM itemcategory
										WHERE id = " . $category_id)->row_array();

		$make_code = $this->make_code($query['name']);

		$item = $this->db->query("SELECT name,
										JSON_EXTRACT(attribute, '$.category_id') AS category_id
										FROM item
										WHERE dept_id = " . $this->session->userdata('dept_id') . "
										AND JSON_EXTRACT(attribute, '$.category_id') = " . $category_id . "
										ORDER BY id DESC
										LIMIT 1");

		$unique = 1;
		if ($item->num_rows() > 0) {
			$item = $item->row_array();

			// if (str_replace('"', '', $item['category_id']) == $category_id) {

				$existing = $item['name'];
				$existing = explode('.', $item['name']);
				$existing_code = (int)$existing[1];

				$unique = $existing_code + 1;
			// }
		}
		
        $fileName = date('YmdHis');
    
        $config['upload_path'] = './images-temp/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $fileName;
            
        $this->load->library('upload',$config);
    
        $this->upload->do_upload("file");
        
        $file = $this->upload->data('file_name');
    
        $file_content = './images-temp/' .  $file;
		
        $image = imagecreatefromstring(file_get_contents($file_content));
        ob_start();
        imagejpeg($image,NULL,100);
        $cont = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        $content = imagecreatefromstring($cont);
        $output = 'images/' . date('ymdhis') . '.webp';
        imagewebp($content,$output);
        imagedestroy($content);

		for ($i = 0; $i < count($color); $i++) {

			$item = $make_code . '.' . $unique . '.' . $color[$i];

			$colorData = $this->db->query("SELECT name
												FROM itemcolor
												WHERE id = " . $color[$i])->row_array();

			$colorName = $colorData['name'];

			$attribute = array(
								'category_id'=> $category_id,
								'name_id' 	=> $unique, 
								'color_id'	=> $color[$i]
			);

			$data = array(
							'dept_id' 	=> $this->session->userdata('dept_id'),
							'name' 		=> $item,
							'attribute' => json_encode($attribute),
							'category'  => $query['name'],
							'code'		=> $item,
							'images'    => $output,
							'created_time' => date('Y-m-d H:i:s'),
							'updated_time' => date('Y-m-d H:i:s'),
							'creator' 		=> $this->session->userdata('user_id'),
							'updated_by' 	=> $this->session->userdata('user_id')
			);

			$this->db->insert('item', $data);

			$dataItem[] =  array(
								'id' 	=>  $this->db->insert_id(),
								'code' 	=> $item,
								'color'	=> $colorName

			);
		
			$this->barcode($item);
			
// 			unlink($file_content);
		}

		echo json_encode($dataItem);
	}
	// End of function save_item

	/**
	 * @param post => item_name
	 * @param post => item_category
	 * @param post => item_color
	 * @param post => select_name
	 * @param post => select_category
	 * @param post => select_color
	 */
	public function save_new_item() {

		// Permission
		$this->session_lib->check_permission('p_item_add');

		$name 		= ucwords($this->input->post('item_name'));
		$category 	= ucwords($this->input->post('item_category'));
		$color 		= ucwords($this->input->post('item_color'));
		$name_id	= ucwords($this->input->post('select_item'));
		$category_id= ucwords($this->input->post('select_category'));
		$color_id   = ucwords($this->input->post('select_color'));

		// is post null
		if (empty($this->input->post('item_name'))) {
			echo 'error-null';
		} else {

			if (empty($name_id)) {

				$this->db->insert('itemname', array('name' => $name, 'dept_id' => $this->session->userdata('dept_id')));
				$name_id = $this->db->insert_id();
			}

			if (empty($category_id)) {

				$this->db->insert('itemcategory', array('name' => $category, 'dept_id' => $this->session->userdata('dept_id')));
				$category_id = $this->db->insert_id();
			}

			if (empty($color_id)) {

				$this->db->insert('itemcolor', array('name' => $color));
				$color_id = $this->db->insert_id();
			}

			$color_code = $color_id;
			if (strlen($color_id) == 1) {
				$color_code = '0' . $color_id;
			}

			$item_name = $category . ' '  .  $name  . ' ' . $color;
			$item_code = $this->make_code($category) . $this->make_code($name) . '-' . $color_code;

			$attribute = array(
								'category_id'=> $category_id,
								'name_id' 	=> $name_id, 
								'color_id'	=> $color_id,
								'category' 	=> $category,
								'name' 		=> $name, 
								'color'		=> $color
			);

			$data = array(
							'dept_id' 	=> $this->session->userdata('dept_id'),
							'name' 		=> $item_name,
							'attribute' => json_encode($attribute),
							'category'  => $category,
							'code'		=> $item_code,
							'created_time' => date('Y-m-d H:i:s'),
							'updated_time' => date('Y-m-d H:i:s'),
							'creator' 		=> $this->session->userdata('user_id'),
							'updated_by' 	=> $this->session->userdata('user_id')
			);

			$this->db->insert('item', $data);
			
			$this->barcode($item_code);
			
			echo 'success';
		}
	}
	// End of function save_new_item

	/**
	 * @param post => item_name
	 * @param post => item_category
	 * @param post => item_color
	 * @param post => select_name
	 * @param post => select_category
	 * @param post => select_color
	 */
	public function update_item($id) {

		// Permission
		$this->session_lib->check_permission('p_item_edit');

		$name 		= ucwords($this->input->post('item_name'));
		$category 	= ucwords($this->input->post('item_category'));
		$color 		= ucwords($this->input->post('item_color'));
		$name_id	= ucwords($this->input->post('select_item'));
		$category_id= ucwords($this->input->post('select_category'));
		$color_id   = ucwords($this->input->post('select_color'));

		// is post null
		if (empty($this->input->post('item_name'))) {
			echo 'error-null';
		} else {
		    
		    //get current item_code 
		    $lastCode = $this->db->query("SELECT code
		                                           FROM item 
		                                           WHERE id = $id")->row_array();
		    $currentCode = $lastCode['code'];

			if (empty($name_id)) {

				$this->db->insert('itemname', array('name' => $name, 'dept_id' => $this->session->userdata('dept_id')));
				$name_id = $this->db->insert_id();
			}

			if (empty($category_id)) {

				$this->db->insert('itemcategory', array('name' => $category, 'dept_id' => $this->session->userdata('dept_id')));
				$category_id = $this->db->insert_id();
			}

			if (empty($color_id)) {

				$this->db->insert('itemcolor', array('name' => $color));
				$color_id = $this->db->insert_id();
			}

			$color_code = $color_id;
			if (strlen($color_id) == 1) {
				$color_code = '0' . $color_id;
			}

			$item_name = $category . ' '  .  $name  . ' ' . $color;
			$item_code = $this->make_code($category) . $this->make_code($name) . '-' . $color_code;

			$attribute = array(
								'category_id'=> $category_id,
								'name_id' 	=> $name_id, 
								'color_id'	=> $color_id,
								'category' 	=> $category,
								'name' 		=> $name, 
								'color'		=> $color
			);

			$data = array(
							'name' 		=> $item_name,
							'attribute' => json_encode($attribute),
							'category'  => $category,
							'code'		=> $item_code,
							'updated_time' => date('Y-m-d H:i:s'),
							'updated_by' 	=> $this->session->userdata('user_id')
			);

			$this->db->where('id', $id);
			$this->db->update('item', $data);
		
			
			//delete barcode on file system
			unlink('./assets/barcode/' . $currentCode . '.png');

			//create barcode 
			$this->barcode($item_code);
			
			echo 'success';
		}
	}
	// End of function update_item

	public function make_code($str) {

		$code =  '';
		$explode = explode('%20', $str);

		for ($i = 0; $i < count($explode); $i++) {

			$substr = substr($explode[$i], 0, 3);
			$code .= $substr;
		}

		return strtoupper($code);
	}
	// End of function make_code

	/**
	 * @param post => item_name
	 * @param post => item_code
	 * @param post => item_category
	 * @param post => item_merk
	 */
	public function save_new_item_() {

		// Permission
		$this->session_lib->check_permission('p_item_add');

		// is post null
		if (empty($this->input->post('item_name'))) {

			echo 'error-null';

		} else {
			
			// Get item by name and dept
			$condition = array('name' => ucwords($this->input->post('item_name')), 'dept_id' => $this->session->userdata('dept_id'));
			$this->db->where($condition);
			$itemByName = $this->db->get('item')->row();

			// is item duplicate
			if (! empty($itemByName)) {

				echo 'error-duplicate-item';

			} else {

				// Get item by code and dept
				$condition = array('code' => ucwords($this->input->post('item_code')), 'dept_id' => $this->session->userdata('dept_id'));
				$this->db->where($condition);
				$itemByCode = $this->db->get('item')->row();

				// is code duplicate
				if (! empty($itemByCode)) {

					echo 'error-duplicate-code';

				} else {

					// insert item
					$data = array(
									'dept_id' 		=> $this->session->userdata('dept_id'),
									'name' 			=> ucwords($this->input->post('item_name')),
									'code'			=> ucwords($this->input->post('item_code')),
									'category' 		=> ucwords($this->input->post('item_category')),
									'merk' 			=> ucwords($this->input->post('item_merk')),
									'created_time' 	=> date('Y-m-d H:i:s'),
									'updated_time' 	=> date('Y-m-d H:i:s'),
									'creator' 		=> $this->session->userdata('user_id'),
									'updated_by' 	=> $this->session->userdata('user_id')
								);

					$this->db->insert('item', $data);

					$this->load->library('ciqrcode'); //pemanggilan library QR CODE

					$config['cacheable'] 	= true; //boolean, the default is true
					$config['cachedir'] 	= './assets/'; //string, the default is application/cache/
					$config['errorlog'] 	= './assets/'; //string, the default is application/logs/
					$config['imagedir'] 	= './assets/barcode/'; //direktori penyimpanan qr code
					$config['quality'] 		= true; //boolean, the default is true
					$config['size'] 		= '1024'; //interger, the default is 1024
					$config['black'] 		= array(224,255,255); // array, default is array(255,255,255)
					$config['white'] 		= array(70,130,180); // array, default is array(0,0,0)
					$this->ciqrcode->initialize($config);

					$image_name				= ucwords($this->input->post('item_code')).'.png'; //buat name dari qr code

					$params['data'] 		= ucwords($this->input->post('item_code')); //data yang akan di jadikan QR CODE
					$params['level'] 		= 'H'; //H=High
					$params['size'] 		= 10;
					$params['savename'] 	= FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/barcode/
					$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

					// Log
					$this->log_activity_lib->activity_record('Barang', 'Add', 'item', $this->db->insert_id(), $this->input->post('item_name'));

					echo 'success';

				}
				// End of if code duplicate

			}
			// End of if item duplicate

		}
		// End of if post null
		
	}
	// End of function save_new_item

	/**
	 * @param $id
	 * @param post => item_name
	 * @param post => item_code
	 * @param post => item_merk
	 * @param post => item_category
	 */
	public function update_item_($id) {

		// Permission
		$this->session_lib->check_permission('p_item_edit');

		// is post null
		if (empty($this->input->post('item_name')) ||
			empty($this->input->post('item_code'))) {

			echo 'error-null';

		} else {

			// Get item by name and ! item_id and dept_id
			// $checkDuplicateItem = $this->db->query("SELECT name
			// 						 					   FROM item
			// 						 					   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
			// 						 					   AND name = '" . ucwords($this->input->post('item_name')) . "'
			// 						 					   AND id != '$id'")->num_rows();

			// // is item duplicate
			// if ($checkDuplicateItem > 0) {

			// 	echo 'error-duplicate-item';

			// } else {

				/* NOTE : name may have duplicate */

				// Get item by name and ! item_code and dept_id
				$checkDuplicateItem = $this->db->query("SELECT code
															   FROM item
															   WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
															   AND code = '" . ucwords($this->input->post('item_code')) . "'
															   AND id != '$id'");

				if ($checkDuplicateItem->num_rows() > 0) {

					echo 'error-duplicate-code';

				} else {

					$item = $this->db->query("SELECT code
											   		 FROM item
											   		 WHERE dept_id = '" . $this->session->userdata('dept_id') . "'
											   		 AND id = " . $id)->row();

					// update item
					$data = array(
									'name' 			=> ucwords($this->input->post('item_name')),
									'code' 			=> ucwords($this->input->post('item_code')),
									'merk' 			=> ucwords($this->input->post('item_merk')),
									'category' 		=> ucwords($this->input->post('item_category')),
									'updated_time' 	=> date('Y-m-d H:i:s'),
									'updated_by' 	=> $this->session->userdata('user_id')
								);

					if ($item->code != ucwords($this->input->post('item_code'))) {

						unlink('./assets/barcode/' . $item->code . '.png');

						$this->load->library('ciqrcode'); //pemanggilan library QR CODE

						$config['cacheable'] 	= true; //boolean, the default is true
						$config['cachedir'] 	= './assets/'; //string, the default is application/cache/
						$config['errorlog'] 	= './assets/'; //string, the default is application/logs/
						$config['imagedir'] 	= './assets/barcode/'; //direktori penyimpanan qr code
						$config['quality'] 		= true; //boolean, the default is true
						$config['size'] 		= '1024'; //interger, the default is 1024
						$config['black'] 		= array(224,255,255); // array, default is array(255,255,255)
						$config['white'] 		= array(70,130,180); // array, default is array(0,0,0)
						$this->ciqrcode->initialize($config);

						$image_name 			= ucwords($this->input->post('item_code')).'.png'; //buat name dari qr code

						$params['data'] 		= ucwords($this->input->post('item_code')); //data yang akan di jadikan QR CODE
						$params['level'] 		= 'H'; //H=High
						$params['size'] 		= 10;
						$params['savename'] 	= FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/barcode/
						$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

					}

					$this->db->where('id', $id);
					$this->db->update('item', $data);

					// Log
					$this->log_activity_lib->activity_record('Barang', 'Edit', 'item', $id, $this->input->post('item_name'));

					echo 'success';

				}
				// End of if code duplicate

			//}
			// End of if item duplicate

		}
		// End of if post null
		
	}
	// End of function update_item

	/**
	 * @param post => id
	 * @return bool
	 */
	public function delete_item() {
		
		// Permission
		$this->session_lib->check_permission('p_item_delete');

		$this->db->trans_start();

		$query = $this->db->query("SELECT name, code FROM item WHERE id = '" . $this->input->post('id') . "'")->row();

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('item');

		// is successful trans
		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			// Log
			$this->log_activity_lib->activity_record('Barang', 'Delete', 'item', $this->input->post('id'), $query->name);

			unlink('./assets/barcode/' . $query->code . '.png');

			$this->db->trans_commit();

			echo 'success';

		}

	}
	// End of function delete_item

	/**
	* 
	*/
	public function view_print() {
		// Permission
		$this->session_lib->check_permission('p_item_report');

		// Log
		$this->log_activity_lib->activity_record('Barang', 'Visit');

		//category item
		$query = $this->db->query("SELECT name FROM itemcategory");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$category[] = $row->name;
			}
		}

		$attr = array(
						'filterCategory' 	=> $this->display_filter_category(),
						'category'			=> $category
					);

		$this->layout_lib->default_template('master/item/view-print', $attr);
	}
	// end of function view_print

	/**
	* @param category
	*/
	public function get_print_item() {
		$category = $this->input->post('category');

		$query = $this->db->query("SELECT name, id, code 
										FROM item 
										WHERE category = '$category'");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$name[] 	= $row->name;
				$id[]		= $row->id;
				$code[]		= $row->code;
			}

			$message = 'success';
		} else {
			$message 	= 'item-null';
			$name 		= '';
			$id 		= '';
			$code 		= '';
		}

		$data['message'] 	= $message;
		$data['name']		= $name;
		$data['id']			= $id;
		$data['code']		= $code;

		echo json_encode($data);
	}
	// end of function get_print_item

	/**
	* @param field-id-print
	* @param field-value-print
	*/
	public function process_print() {
		$value 	= $this->input->post('value');
		$code 	= $this->input->post('code');

		if ($value == '') {
			echo 'array-null';
		} else if ($value > 3) {
			echo 'array-err';
		} else {
			for ($i = 0; $i < ($value * 3); $i++) {
				$img[] 			= base_url() . 'assets/barcode/' . $code . '.png';
				$code_print[] 	= $code;
			}

			$data['img'] 	= $img;
			$data['code']	= $code_print;

			$this->load->view('master/item/print-view', $data);
		}

	}
	// end of function process_print

	/**
	* 
	*/
	public function get_print() {
		$id = $_POST['id'];

		$result = $this->db->query("SELECT code, name FROM item WHERE id = $id")->row_array();

		$code = $result['code'];
		$name = $result['name'];

		$img 	= base_url() . 'assets/barcode/' . $code . '.png';

		$data['img']	= $img;
		$data['name']	= $name;
		$data['code']	= $code;

		echo json_encode($data);

	}
	// end of function view_print

}
/* End of file Item.php */
/* Location: ./application/controllers/Item.php */
