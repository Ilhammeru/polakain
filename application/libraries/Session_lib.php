<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Session_lib
 */
class Session_lib {

	protected $CI;
	
	function __construct() {

		$this->CI =& get_instance();

	}
	// End of function __construct

	/**
	 * @param $username
	 */
	public function set_sessions($username) {

		// Get fields in permission table
		$permissionFields = $this->CI->db->list_fields('permission');

		$fields = "";

		foreach ($permissionFields as $field) :

			if(substr($field, 0, 2) == 'p_') {

				$fields .= $field . ", ";

			}

		endforeach;

		// Get permission data
		$row = $this->CI->db->query("SELECT users.dept_id,
											users.id AS user_id,
											" . $fields . "
											role.name AS role_name
											FROM users
											JOIN role ON role.id = users.role_id
											JOIN permission ON permission.role_id = role.id
											WHERE username = '" . $username . "'")->row();

		// Set sessions
		$data = array(	
						'dept_id' => $row->dept_id,
						'user_id' => $row->user_id,
						'username' => $username,
						'role_name' => $row->role_name,
						'p_log_activity' => $row->p_log_activity,
						'p_role_report' => $row->p_role_report,
						'p_role_add' => $row->p_role_add,
						'p_role_edit' => $row->p_role_edit,
						'p_role_delete' => $row->p_role_delete,
						'p_user_report' => $row->p_user_report,
						'p_user_add' => $row->p_user_add,
						'p_user_edit' => $row->p_user_edit,
						'p_user_delete' => $row->p_user_delete,
						'p_vendor_report' => $row->p_vendor_report,
						'p_vendor_add' => $row->p_vendor_add,
						'p_vendor_edit' => $row->p_vendor_edit,
						'p_vendor_delete' => $row->p_vendor_delete,
						'p_warehouse_report' => $row->p_warehouse_report,
						'p_warehouse_add' => $row->p_warehouse_add,
						'p_warehouse_edit' => $row->p_warehouse_edit,
						'p_warehouse_delete' => $row->p_warehouse_delete,
						'p_item_report' => $row->p_item_report,
						'p_item_add' => $row->p_item_add,
						'p_item_edit' => $row->p_item_edit,
						'p_item_delete' => $row->p_item_delete,
						'p_payment_method_report' => $row->p_payment_method_report,
						'p_payment_method_add' => $row->p_payment_method_add,
						'p_payment_method_edit' => $row->p_payment_method_edit,
						'p_payment_method_delete' => $row->p_payment_method_delete,
						'p_sale_price_report' => $row->p_sale_price_report,
						'p_sale_price_edit' => $row->p_sale_price_edit,
						'p_template_item' => $row->p_template_item,
						'p_invoice_report' => $row->p_invoice_report,
						'p_invoice_add' => $row->p_invoice_add,
						'p_invoice_edit' => $row->p_invoice_edit,
						'p_invoice_delete' => $row->p_invoice_delete,
						'p_payment_dp_report' => $row->p_payment_dp_report,
						'p_payment_dp_add' => $row->p_payment_dp_add,
						'p_payment_dp_delete' => $row->p_payment_dp_delete,
						'p_payment_report' => $row->p_payment_report,
						'p_payment_approval' => $row->p_payment_approval,
						'p_payment_cancel' => $row->p_payment_cancel,
						'p_sale_add' => $row->p_sale_add,
						'p_sale_edit' => $row->p_sale_edit,
						'p_sale_report' => $row->p_sale_report,
						'p_sale_delete' => $row->p_sale_delete,
						'p_approval_report' => $row->p_approval_report,
						'p_approval_submit' => $row->p_approval_submit,
						'p_approval_cancel' => $row->p_approval_cancel,
						'p_storage_report' => $row->p_storage_report,
						'p_storage_approval' => $row->p_storage_approval,
						'p_storage_cancel' => $row->p_storage_cancel,
						'p_move_item_report' => $row->p_move_item_report,
						'p_move_item_add' => $row->p_move_item_add,
						'p_move_item_delete' => $row->p_move_item_delete,
						'p_update_stock_add' => $row->p_update_stock_add,
						'p_update_stock_edit' => $row->p_update_stock_edit,
						'p_buku_besar_report' => $row->p_buku_besar_report,
						'p_buku_besar_approval' => $row->p_buku_besar_approval,
						'p_warehouse_id' => $row->p_warehouse_id
					);

		$this->CI->session->set_userdata($data);

	}
	// End of function set_sessions

	/**
	 * @return true or redirect
	 */
	public function check_session() {

		// is already sessions
		if (! $this->CI->session->userdata('user_id')) {

			$this->CI->session->set_flashdata('alert-error', 'Sesi telah berakhir');

			redirect('sessions/signin');

		} else {

			return true;

		}
		// End of if already sessions
		
	}
	// End of function check_session

	/**
	 * @return bool or redirect
	 */
	public function check_permission($key) {
		
		// is have permission
		if ($this->CI->session->userdata($key) == 1) {

			// 1 is have permission
			return true;

		} else {

			// 0 is not have permission => redirect to error page
			redirect('error_handler');

		}

	}
	// End of function check_permission

	/**
	 * @return bool or redirect
	 */
	public function check_two_permission($condition = '', $key1, $key2) {
		
		switch ($condition) :

			case 'or' :

				// is have permission
				if ($this->CI->session->userdata($key1) == 1 || $this->CI->session->userdata($key2) == 1) {

					// 1 is have permission
					return true;

				} else {

					// 0 is not have permission => redirect to error page
					redirect('error_handler');

				}

				break;

			case 'and' :

				// is have permission
				if ($this->CI->session->userdata($key1) == 1 && $this->CI->session->userdata($key2) == 1) {

					// 1 is have permission
					return true;

				} else {

					// 0 is not have permission => redirect to error page
					redirect('error_handler');

				}

				break;

		endswitch;

	}
	// End of function check_two_permission

}
/* End of file Session_lib.php */
/* Location: ./application/libraries/Session_lib.php/ */

