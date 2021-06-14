<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Server_side_lib
 */
class Server_side_lib {

	/*
		Parameter
		Format Date => timestamp (for date or datetime), integer, string (default)
		Filter Type => daterange (for date or datetime), slider, select-multiple, search (default)
	*/
	
	protected $CI;

	function __construct() {

		$this->CI =& get_instance();

	}
	// End of function __construct

	/**
	 * @param $columnSearch
	 * @return string
	 */
    function individual_column_filtering($columnSearch = '', $tb) {

   		$x = false;

		for ($i = 0; $i < count($columnSearch); $i++) :

			if ($_POST['columns'][$i]['search']['value']) :

				$x = true;

			endif;

		endfor;

		if ($x == true) :

			$sintax = "WHERE ( " . $tb . ".dept_id = '" . $this->CI->session->userdata('dept_id') . "' AND ";

		else :

			$sintax = "WHERE ( " . $tb . ".dept_id = '" . $this->CI->session->userdata('dept_id') . "')";

		endif;

		$z = 0;

		for ($i = 0; $i < count($columnSearch); $i++) :

			if (! empty($columnSearch[$i]['format'])) :

				if ($_POST['columns'][$i]['search']['value']) :

				    $searchKey[$i] = $_POST['columns'][$i]['search']['value'];

				    if ($z == 0) :

				    	$prefix = "";

				    else :

				    	$prefix = "AND ";

				    endif;

				    switch ($columnSearch[$i]['format']) :
				    	/*
				    	case 'timestamp' :

				        	$convert = "::timestamp::date";

				        	break;

				        case 'integer' :

				        	$convert = "::text";

				        	break;

				        case 'boolean' :

				        	$convert = "::text";

				        	break;
						*/
				        default :

				        	$convert = "";

				        	break;

					endswitch;

					switch ($columnSearch[$i]['type']) :

						case 'daterange' :

							$explode = explode(" - ", $searchKey[$i]);

					        $searchKey0 = date_format(date_create($explode[0]), 'Y-m-d');

					        $searchKey1 = date_format(date_create($explode[1]), 'Y-m-d');

					        $searchKey0 = $searchKey0 . ' 00:00:00';
					        $searchKey1 = $searchKey1 . ' 23:59:59';

					    	$sintax .= $prefix . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " >= '" . $searchKey0 . "' AND " . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

						case 'slider' :

							$explode = explode(",", $searchKey[$i]);

					        $searchKey0 = $explode[0];

					        $searchKey1 = $explode[1];

					    	$sintax .= $prefix . $columnSearch[$i]['field'] . $convert . " >= '" . $searchKey0 . "' AND " . $columnSearch[$i]['field'] . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

					    case 'select-multiple' :

					    	$sintax .= $prefix;

					    	$explode = explode(",", $searchKey[$i]);

					    	for ($a = 0; $a < count($explode); $a++) :

					    		if ($a == 0) :
					    			
					    			$sintax .= "(";

					    		endif;

					    		if ($a > 0 AND $a != count($explode)) :

					    			$sintax .= " OR ";

					    		endif;

					    		$sintax .= $columnSearch[$i]['field'] . $convert . " = '" . $explode[$a] . "'";

					    		if ($a == count($explode) - 1) :
					    			
					    			$sintax .= ") ";

					    		endif;

					    	endfor;

					    	break;

					    default :
				    		
				    		$sintax .= $prefix . $columnSearch[$i]['field'] . $convert . " LIKE '%" . $searchKey[$i] . "%' ESCAPE '!' ";

					    	break;

					endswitch;

				    $z = 1;

				endif;

			endif;

		endfor;

		if ($x == true) :

			$sintax .= ") ";

		endif;

		return $sintax;

    }
    // End of function individual_column_filtering

    /**
	 * @param $columnSearch
	 * @return string
	 */
    function individual_column_filtering_default($columnSearch = '') {

   		$x = false;

   		$sintax = "";

		for ($i = 0; $i < count($columnSearch); $i++) :

			if ($_POST['columns'][$i]['search']['value']) :

				$x = true;

			endif;

		endfor;

		if ($x == true) :

			$sintax = "WHERE ( ";

		endif;

		$z = 0;

		for ($i = 0; $i < count($columnSearch); $i++) :

			if (! empty($columnSearch[$i]['format'])) :

				if ($_POST['columns'][$i]['search']['value']) :

				    $searchKey[$i] = $_POST['columns'][$i]['search']['value'];

				    if ($z == 0) :

				    	$prefix = "";

				    else :

				    	$prefix = "AND ";

				    endif;

				    switch ($columnSearch[$i]['format']) :
				    	/*
				    	case 'timestamp' :

				        	$convert = "::timestamp::date";

				        	break;

				        case 'integer' :

				        	$convert = "::text";

				        	break;

				        case 'boolean' :

				        	$convert = "::text";

				        	break;
						*/
				        default :

				        	$convert = "";

				        	break;

					endswitch;

					switch ($columnSearch[$i]['type']) :

						case 'daterange' :

							$explode = explode(" - ", $searchKey[$i]);

					        $searchKey0 = date_format(date_create($explode[0]), 'Y-m-d');

					        $searchKey1 = date_format(date_create($explode[1]), 'Y-m-d');

					        $searchKey0 = $searchKey0 . ' 00:00:00';
					        $searchKey1 = $searchKey1 . ' 23:59:59';

					    	$sintax .= $prefix . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " >= '" . $searchKey0 . "' AND " . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

						case 'slider' :

							$explode = explode(",", $searchKey[$i]);

					        $searchKey0 = $explode[0];

					        $searchKey1 = $explode[1];

					    	$sintax .= $prefix . $columnSearch[$i]['field'] . $convert . " >= '" . $searchKey0 . "' AND " . $columnSearch[$i]['field'] . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

					    case 'select-multiple' :

					    	$sintax .= $prefix;

					    	$explode = explode(",", $searchKey[$i]);

					    	for ($a = 0; $a < count($explode); $a++) :

					    		if ($a == 0) :
					    			
					    			$sintax .= "(";

					    		endif;

					    		if ($a > 0 AND $a != count($explode)) :

					    			$sintax .= " OR ";

					    		endif;

					    		$sintax .= $columnSearch[$i]['field'] . $convert . " = '" . $explode[$a] . "'";

					    		if ($a == count($explode) - 1) :
					    			
					    			$sintax .= ") ";

					    		endif;

					    	endfor;

					    	break;

					    default :
				    		
				    		$sintax .= $prefix . $columnSearch[$i]['field'] . $convert . " LIKE '%" . $searchKey[$i] . "%' ESCAPE '!' ";

					    	break;

					endswitch;

				    $z = 1;

				endif;

			endif;

		endfor;

		if ($x == true) :

			$sintax .= ") ";

		endif;

		return $sintax;

    }
    // End of function individual_column_filtering_default

    /**
	 * @param $columnSearch
	 * @return string
	 */
    function individual_column_filtering_attend_late($columnSearch = '') {

   		$x = false;

		for ($i = 0; $i < count($columnSearch); $i++) :

			if ($_POST['columns'][$i]['search']['value']) :

				$x = true;

			endif;

		endfor;

		if ($x == true) :

			$sintax = "WHERE ( attend_status = 'TERLAMBAT' AND ";

		else :

			$sintax = "WHERE ( attend_status = 'TERLAMBAT' )";

		endif;

		$z = 0;

		for ($i = 0; $i < count($columnSearch); $i++) :

			if (! empty($columnSearch[$i]['format'])) :

				if ($_POST['columns'][$i]['search']['value']) :

				    $searchKey[$i] = $_POST['columns'][$i]['search']['value'];

				    if ($z == 0) :

				    	$prefix = "";

				    else :

				    	$prefix = "AND ";

				    endif;

				    switch ($columnSearch[$i]['format']) :
				    	/*
				    	case 'timestamp' :

				        	$convert = "::timestamp::date";

				        	break;

				        case 'integer' :

				        	$convert = "::text";

				        	break;

				        case 'boolean' :

				        	$convert = "::text";

				        	break;
						*/
				        default :

				        	$convert = "";

				        	break;

					endswitch;

					switch ($columnSearch[$i]['type']) :

						case 'daterange' :

							$explode = explode(" - ", $searchKey[$i]);

					        $searchKey0 = date_format(date_create($explode[0]), 'Y-m-d');

					        $searchKey1 = date_format(date_create($explode[1]), 'Y-m-d');

					        $searchKey0 = $searchKey0 . ' 00:00:00';
					        $searchKey1 = $searchKey1 . ' 23:59:59';

					    	$sintax .= $prefix . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " >= '" . $searchKey0 . "' AND " . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

					    case 'singledate' :

					        $searchKey0 = $searchKey[$i] . ' 00:00:00';
					        $searchKey1 = $searchKey[$i] . ' 23:59:59';

					    	$sintax .= $prefix . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " >= '" . $searchKey0 . "' AND " . 'DATE_FORMAT(' . $columnSearch[$i]['field'] . ', "%Y-%m-%d %H:%i:%s")' . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

						case 'slider' :

							$explode = explode(",", $searchKey[$i]);

					        $searchKey0 = $explode[0];

					        $searchKey1 = $explode[1];

					    	$sintax .= $prefix . $columnSearch[$i]['field'] . $convert . " >= '" . $searchKey0 . "' AND " . $columnSearch[$i]['field'] . $convert . " <= '" . $searchKey1 . "' ";

					    	break;

					    case 'select-multiple' :

					    	$sintax .= $prefix;

					    	$explode = explode(",", $searchKey[$i]);

					    	for ($a = 0; $a < count($explode); $a++) :

					    		if ($a == 0) :
					    			
					    			$sintax .= "(";

					    		endif;

					    		if ($a > 0 AND $a != count($explode)) :

					    			$sintax .= " OR ";

					    		endif;

					    		$sintax .= $columnSearch[$i]['field'] . $convert . " = '" . $explode[$a] . "'";

					    		if ($a == count($explode) - 1) :
					    			
					    			$sintax .= ") ";

					    		endif;

					    	endfor;

					    	break;

					    default :
				    		
				    		$sintax .= $prefix . $columnSearch[$i]['field'] . $convert . " LIKE '%" . $searchKey[$i] . "%' ESCAPE '!' ";

					    	break;

					endswitch;

				    $z = 1;

				endif;

			endif;

		endfor;

		if ($x == true) :

			$sintax .= ") ";

		endif;

		return $sintax;

    }
    // End of function individual_column_filtering_attend_late

    /**
     * @param $columnOrder
     * @param $order
     * @return string
     */
    function ordering($columnOrder = '', $order = '') {
         
        if (isset($_POST['order'])) {

            $sintax = "ORDER BY " . $columnOrder[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];

        } elseif (isset($order)) {

            $sintax = "ORDER BY " . key($order) . " " . $order[key($order)];

        }

        return $sintax;

    }
    // End of function ordering

    /**
     * @return string
     */
    function limit() {

    	if($_POST['length'] != -1) {

        	$sintax = " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];

        } else {

        	$sintax = "";

        }

        return $sintax;

    }
    // End of function limit

}
/* End of file Server_side_lib.php */
/* Location: ./application/libraries/Server_side_lib.php */
