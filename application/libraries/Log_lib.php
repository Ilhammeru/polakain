<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Log_lib
 */
class Log_lib {

	protected $CI;
	
	function __construct() {
		$this->CI =& get_instance();
	}
	// End of function 
	
	/**
	 * @param get => param
	 * @param get => object
	 * @param get => param_before
	 * @param get => param_after
	 * 
	 * Conditional param
	 * 1. Shift
	 * 2. Absen
	 * 3. Feeling
	 */
	public function log($param, $object, $param_before, $param_after, $date = '') {

		$username = $this->CI->session->userdata('username');

		$queryObject = $this->CI->db->query("SELECT name
											FROM ac_payroll_item
											WHERE id = " . $object)->row_array();

		switch ($param) :
			case 1:
				$remark = 'shift';
				break;
			case 2:
				$remark = 'absen ' . $date;
				break;
			case 3:
				$remark = 'feeling ' . $date;
				break;
		endswitch;

		$object = $queryObject['name'];

		$log = date('Y-m-d H:i:s') . ' ' . $username . ' telah mengubah ' . $remark . ' ' . $object . ' dari ' . $param_before . ' menjadi ' . $param_after;

		$file = fopen("./logs/log-" . date('Y-m-d') . ".txt", "a");
		echo fwrite($file, $log . PHP_EOL);
		fclose($file);
	}
	// End of function log

}
/* End of file Log_lib.php */
/* Location: ./application/libraries/Log_lib.php/ */