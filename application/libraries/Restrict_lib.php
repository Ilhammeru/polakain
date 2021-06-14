<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Restrict_lib
 */
class Restrict_lib {

	protected $CI;
	
	function __construct() {
		$this->CI =& get_instance();
	}
	// End of function __construct

    public function query_access() {

        $department     = $this->CI->session->userdata('department');
        $access_allowed = $this->CI->session->userdata('access_allowed');
        $seri           = $this->CI->session->userdata('seri');

        switch ($access_allowed) :

            case 1:
                $sintax = "SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS 'name',
							CONCAT('t', ansena_team.id) AS 'id'
							FROM ansena_team 
							LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id 
							UNION ALL 
							SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
                            CONCAT('d', ansena_department.id) AS 'id'
                            FROM ansena_department 
                            LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
                            WHERE ansena_team.id IS NULL";
                break;
            
            case 2:
                $sintax = "SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS name,
                            CONCAT('t', ansena_team.id) AS 'id'
                            FROM ansena_team 
                            JOIN ansena_department ON ansena_department.id = ansena_team.dept_id
                            AND seri = " . $seri . " 
                            UNION ALL 
                            SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
                            CONCAT('d', ansena_department.id) AS 'id'
                            FROM ansena_department 
                            LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
                            WHERE ansena_team.id IS NULL
                            AND seri = " . $seri;
                break;

            case 3:
                $sintax = "SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS 'name',
							CONCAT('t', ansena_team.id) AS 'id'
							FROM ansena_team 
							LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id 
                            WHERE ansena_department.id = " . $department . "
							UNION ALL 
							SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
                            CONCAT('d', ansena_department.id) AS 'id'
                            FROM ansena_department 
                            LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
                            WHERE ansena_team.id IS NULL
                            AND ansena_department.id = " . $department;
                break;
        endswitch;

        return $sintax;
    }
    // End of function query_access

}
/* End of file Restrict_lib.php */
/* Location: ./application/libraries/Restrict_lib.php/ */