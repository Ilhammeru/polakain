<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Sessions
 */
class Sessions extends CI_Controller {

	function __construct() {

		parent::__construct();

		// is session user_id
		if ($this->session->userdata('user_id')) {

			redirect('dashboard');

		}

	}
	// End of function __construct

	public function signin() {

		// is submit post
		if ($this->input->post('btn-login')) {

			// is empty post
			if (empty($this->input->post('username')) || empty($this->input->post('password'))) {

				$this->session->set_flashdata('alert-error', 'Username & password harus diisi');
				$this->session->set_flashdata('animation-error', 'shake-horizontal');

				redirect('sessions/signin');

			} else {

				$this->db->where('username', $this->input->post('username'));
				$user = $this->db->get('users')->row();

				// is empty user
				if (empty($user)) {

					$this->session->set_flashdata('alert-error', 'Username tidak terdaftar');
					$this->session->set_flashdata('animation-error', 'shake-horizontal');

					redirect('sessions/signin');

				} else {

					// Auth
					if (md5(md5($this->input->post('password'))) === $user->password) {

						// Set Session
						$this->session_lib->set_sessions($this->input->post('username'));

						// 0 is empty throttle
						if ($this->throttle_lib->check_throttle() == 0) {

							$this->throttle_lib->insert_throttle('Sign In', 'Sign In');

						} else {

							$this->throttle_lib->update_throttle('Sign In', 'Sign In');

						}

						// Log
						$this->log_activity_lib->activity_record('Sign In', 'Sign In');

						$this->session->set_flashdata('alert-success', 'Login berhasil');

						// Redirect
						redirect('dashboard');

					} else {

						$this->session->set_flashdata('alert-error', 'Password salah');
						$this->session->set_flashdata('animation-error', 'shake-horizontal');

						redirect('sessions/signin');

					}
					// End of file auth

				}	
				// End of if user not found

			}
			// End of if empty username || password

		}
		// End of if post btn-login

		if ($this->input->get('logout') == 1) {

			$this->session->set_flashdata('alert-success', 'Logout berhasil');
			redirect('sessions/signin');

		}

		$this->session->set_flashdata('animation', 'slide-top');

		$this->load->view('sessions/signin');
		
	}
	// End of function signin

}
/* End of file Sessions.php */
/* Location: ./application/controllers/Sessions.php/ */

