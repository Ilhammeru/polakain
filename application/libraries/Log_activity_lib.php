<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Log_activity_lib
 */
class Log_activity_lib {

	protected $CI;
	
	function __construct() {

		$this->CI =& get_instance();

	}
	// End of function __construct

	/**
	 * @param $accessName
	 * @param $activityName
	 * @param $tableKey
	 * @param $fieldKey
	 * @param $remark
	 */
	public function activity_record($accessName, $activityName, $tableKey = '', $fieldKey = '', $remark = '') {

		switch ($accessName) :

			case 'Users' :

				$remarks = 'User [' . $remark . ']';

				break;

			case 'Barang' :

				$remarks = 'Barang [' . $remark . ']';

				break;

			case 'Vendor' :

				$remarks = 'Vendor [' . $remark . ']';

				break;

			case 'Gudang' :

				$remarks = 'Gudang [' . $remark . ']';

				break;

			case 'Metode Pembayaran' :

				$remarks = 'Metode Pembayaran [' . $remark . ']';

				break;

			case 'Harga Jual' :

				$remarks = 'Harga Jual [' . $remark . ']';

				break;

			case 'Template' :

				$remarks = 'Template [' . $remark . ']';

				break;

			case 'Invoice Barang Datang' :

				$remarks = 'Invoice Barang Datang [' . $remark . ']';

				break;

			case 'Pembayaran Dimuka' :

				$remarks = 'Pembayaran Dimuka [' . $remark . ']';

				break;

			case 'Hutang Dagang' :

				$remarks = 'Hutang Dagang [' . $remark . ']';

				break;

			case 'Penjualan' :

				$remarks = 'Penjualan [' . $remark . ']';

				break;

			case 'Data Penjualan' :

				$remarks = 'Data Penjualan [' . $remark . ']';

				break;

			case 'Approval' :

				$remarks = 'Approval [' . $remark . ']';

				break;

			case 'Storage' :

				$remarks = 'Storage [' . $remark . ']';

				break;

			case 'Pindah Barang' :

				$remarks = 'Pindah Barang [' . $remark . ']';

				break;

			case 'Update Stock' :

				$remarks = 'Update Stock [' . $remark . ']';

				break;

			case 'Buku Besar' :

				$remarks = 'Buku Besar [' . $remark . ']';

				break;
 
		endswitch;

		switch ($activityName) :

			case 'Sign In' :

				$log = 'Telah masuk aplikasi';

				break;

			case 'Sign Out' :

				$log = 'Telah keluar aplikasi';

				break;

			case 'Visit' :

				$log = 'Telah mengakses halaman ' . $accessName;

				break;

			case 'Change Password' :

				$log = 'Telah mengubah password';

				break;

			case 'Add' :

				$log = 'Telah menambah data ' . $remarks;

				break;

			case 'Edit' :

				$log = 'Telah mengubah data ' . $remarks;

				break;

			case 'Delete' :

				$log = 'Telah menghapus data ' . $remarks;

				break;

			case 'Approve Data' :

				$log = 'Telah menyetujui data ' . $remarks;

				break;

			case 'Cancel Approve Data' :

				$log = 'Telah cancel data ' . $remarks;

				break;

			case 'Approve Pay' :

				$log = 'Telah menyetujui pembayaran ' . $remarks;

				break;

			case 'Cancel Approve Pay' :

				$log = 'Telah cancel pembayaran ' . $remarks;

				break;

		endswitch;

		// insert log
		$data = array(
						'user_id' => $this->CI->session->userdata('user_id'),
						'time' => date('Y-m-d H:i:s'),
						'log' => $log,
						'access_name' => $accessName,
						'activity_name' => $activityName,
						'ip_address' => $this->CI->input->ip_address(),
						'table_key' => $tableKey,
						'field_key' => $fieldKey
					);

		$this->CI->db->insert('log_activity', $data);

		// update throttle
		$throttleData = array(
								'ip_address' => $this->CI->input->ip_address(),
								'access' => $accessName,
								'activity' => date('Y-m-d H:i:s')
							);

		$this->CI->db->where('user_id', $this->CI->session->userdata('user_id'));
		$this->CI->db->update('throttle', $throttleData);

	}
	// End of function activity_record

}
/* End of file Log_activity.php */
/* Location: ./application/libraries/Log_activity.php/ */
