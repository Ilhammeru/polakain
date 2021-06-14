<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Throttle_lib
 */
class Throttle_lib {

	protected $CI;
	
	function __construct() {

		$this->CI =& get_instance();

	}
	// End of function __construct

	/**
	 * @return bool
	 */
	public function check_throttle() {

		$this->CI->db->where('user_id', $this->CI->session->userdata('user_id'));

		$throttle = $this->CI->db->get('throttle')->row();

		// is empty throttle
		if (empty($throttle)) {

			// is empty => insert throttle
			return 0;

		} else {

			// is not empty => update throttle
			return 1;

		}
		
	}
	// End of function check_throttle

	/**
	 * @param $accessName
	 */
	public function insert_throttle($accessName) {

		$data = array(
						'user_id' => $this->CI->session->userdata('user_id'),
						'ip_address' => $this->CI->input->ip_address(),
						'login_time' => date('Y-m-d H:i:s'),
						'activity' => date('Y-m-d H:i:s'),
						'access' => $accessName
					);

		$this->CI->db->insert('throttle', $data);
		
	}
	// End of function insert_throttle

	/**
	 * @param $accessName
	 */
	public function update_throttle($accessName) {

		$data = array(
						'ip_address' => $this->CI->input->ip_address(),
						'login_time' => date('Y-m-d H:i:s'),
						'activity' => date('Y-m-d H:i:s'),
						'access' => $accessName
					);
	
		$this->CI->db->where('user_id', $this->CI->session->userdata('user_id'));
		$this->CI->db->update('throttle', $data);

	}
	// End of function update_throttle

}
/* End of file Throttle_lib.php */
/* Location: ./application/libraries/Throttle_lib.php/ */

