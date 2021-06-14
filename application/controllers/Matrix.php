<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Matrix
 */
class Matrix extends CI_Controller {

    public function index() {

        $menu = array(
                        '1' => 'Perbandingan HPP & Harga Jual',
                        '2' => 'Total Penjualan'
                    );

        $attr = array(
                        'menu' => $menu
                    );

        $this->layout_lib->template_with_custom_navbar('matrix/navbar-sub-hpp', 'matrix/index', $attr);

    }
    // End of function index

    public function hpp_hj() {

        $date_yesterday = date('Y-m-d', strtotime('-1 days'));
        
        $date = $this->db->query("SELECT date_buku_besar
										  FROM buku_besar
                                          WHERE dept_id = " . $this->session->userdata('dept_id') . "
                                          ORDER BY date_buku_besar DESC");

        if ($date->num_rows() > 0) {
            $date = $date->row_array();
            $date = $date['date_buku_besar'];
        } else {
            $date =  "";
        }

        $basic = $this->db->query("SELECT detail
										  FROM buku_besar
										  WHERE dept_id = " . $this->session->userdata('dept_id') . "
                                          AND DATE_FORMAT(date_buku_besar, '%Y-%m-%d') = '" . $date . "'");
        
        $arrayHpp = array();
        if ($basic->num_rows() > 0) {

            $x = $basic->result();
            
            foreach ($x as $row) :

                if($row->detail != 'null') {

                    $detail = json_decode($row->detail, TRUE);

                    foreach ($detail as $key => $row) :

                        $countRow = count($detail[$key]) -1;
                        $arrayHpp[$key] = floatval($row[$countRow]['rprice']);

                    endforeach;

                }

            endforeach;
		}

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
        
        $attr = array(
                        'template'      => $template,
                        'arrayItem'     => $arrayItem,
                        'detailPrice'   => $detailPrice,
                        'arrayHpp'      => $arrayHpp
                    );

        $this->load->view('matrix/hpp-hj', $attr);
        
    }
    // End of function hpp_hj

    public function get_total_penjualan() {

        $date = $this->input->post('date');

        $date_explode = explode(' - ', $date);

        $date_1 = date('Y-m-d', strtotime($date_explode[0]));
        $date_2 = date('Y-m-d', strtotime($date_explode[1]));

        if ($date_1 == $date_2) {

            $query = $this->db->query("SELECT sale.id, detail
                                            FROM sale
                                            JOIN sale_detail ON sale.id = sale_detail.sale_id
                                            WHERE DATE_FORMAT(date_kirim, '%Y-%m-%d') = '" . $date_1 . "'
                                            AND dept_id = " . $this->session->userdata('dept_id'));

        } else {

            $query = $this->db->query("SELECT sale.id, detail
                                            FROM sale
                                            JOIN sale_detail ON sale.id = sale_detail.sale_id
                                            WHERE DATE_FORMAT(date_kirim, '%Y-%m-%d') >= '" . $date_1 . "' AND
                                            DATE_FORMAT(date_kirim, '%Y-%m-%d') <= '" . $date_2 . "'
                                            AND dept_id = " . $this->session->userdata('dept_id'));
        
        }
        
        if ($query->num_rows() > 0) {

		    $template = $this->db->query("SELECT id, CONCAT(brand, ' - ', tipe) AS 'brand', detail
											 FROM template_item
											 WHERE dept_id = " . $this->session->userdata('dept_id') . "
                                             ORDER BY brand, tipe ASC")->result();

            $item = $this->db->query("SELECT id, name
                                            FROM item
                                            WHERE dept_id = " . $this->session->userdata('dept_id') . "
                                            ORDER BY name ASC")->result();
            
            $templateMaster = array();

            foreach ($template as $row) {
                $templateMaster['t' . $row->id] = $row->brand;
            }

            $itemMaster = array();

            foreach ($item as $row) {
                $itemMaster['i' . $row->id] = $row->name;
            }

            $arrayTemplate = array();
            $arrayItem = array();

            $result = $query->result();

            foreach ($result as $row) :

                $key_master = 'm' . $row->id;

                $detail = json_decode($row->detail);

                $template_id = '';
                $item_id = '';
                $item_qty = array();

                foreach ($detail as $list) {
                        
                    $key_t = 't' . $list->template_id;

                    if (isset($templateMaster[$key_t])) {
                        $templateName = $templateMaster[$key_t];
                    }

                    $arrayTemplate[$key_t][$key_master] = array(
                                                    "template_id"       => $list->template_id,
                                                    "template_name"     => $templateName,
                                                    "template_qty"      => $list->template_qty,
                                                    "template_price"    => $list->template_price
                                                );

                    $key_i = 'i' . $list->item_id;

                    if (isset($itemMaster[$key_i])) {
                        $itemName = $itemMaster[$key_i];
                    } else {
                        $itemName = '???';
                    }

                    if (!array_key_exists($key_i, $arrayItem)) {

                        $arrayItem[$key_i] = array(
                                            "item_id"   => $list->item_id,
                                            "item_name" => $itemName,
                                            "item_qty"  => $list->qty
                                        );

                    } else {
                        $arrayItem[$key_i]["item_qty"] = $arrayItem[$key_i]["item_qty"] + $list->qty;
                    }

                    $template_id = $list->template_id;
                    $item_id = $list->item_id;

                }

            endforeach;

            $arrayTemplateGroup = array();

            foreach ($arrayTemplate as $key => $value) :

                foreach ($value as $x) :

                    if (!array_key_exists($key, $arrayTemplateGroup)) {
                        if ($x['template_qty'] != null && $x['template_qty'] != 'undefined') {
                            $arrayTemplateGroup[$key] = array(
                                                                'template_name' => $x['template_name'],
                                                                'template_qty'  => $x['template_qty']
                                                            );
                        }
                    } else {
                        if ($x['template_qty'] != null && $x['template_qty'] != 'undefined') {
                            $arrayTemplateGroup[$key]['template_qty'] += $x['template_qty'];
                        }
                    }

                endforeach;

            endforeach;

		    $array_column_1 = array_column($arrayItem, 'item_name');
            array_multisort($array_column_1, SORT_ASC, $arrayItem);

            $array_column_2 = array_column($arrayTemplateGroup, 'template_name');
            array_multisort($array_column_2, SORT_ASC, $arrayTemplateGroup);
            
            $attr = array(
                            'templateData'  => json_encode($arrayTemplateGroup),
                            'itemData'      => json_encode($arrayItem),
                            'date_1'        => date('d M Y', strtotime($date_1)),
                            'date_2'        => date('d M Y', strtotime($date_2))
                        );

            $this->load->view('matrix/display-total-penjualan', $attr);

        }

    }
    // End of function get_total_penjualan
    

}
/* End of file Matrix.php */
/* Location: ./application/controllers/Matrix.php */
