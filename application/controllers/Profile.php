<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Profile
 */
class Profile extends CI_Controller {

    public function index() {

        $this->load->view('profile/index');

    }// End of function index

}

