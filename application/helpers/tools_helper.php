<?php

	function lock_closing($param) {

		$lock = 0;

		if ($lock == 1) {

			switch ($param) :

				case 'Invoice':

					if (date('D') == 'Sat') {
			            $closingDate = '15:00:00';
			        } else {
			            $closingDate = '16:00:00';
			        }

					break;

				case 'Payment DP':

					if (date('D') == 'Sat') {
			            $closingDate = '15:00:00';
			        } else {
			            $closingDate = '16:00:00';
			        }

					break;

				case 'Payment':

					if (date('D') == 'Sat') {
			            $closingDate = '15:00:00';
			        } else {
			            $closingDate = '16:00:00';
			        }

					break;

				case 'Sales':

					if (date('D') == 'Sat') {
						$closingDate = '14:15:00';
					} else {
						$closingDate = '23:30:00';
					}

					break;

				case 'Approval':

					if (date('D') == 'Sat') {
				        $closingDate = '14:45:00';
				    } else {
				        $closingDate = '15:45:00';
				    }

					break;

				case 'Storage':

					if (date('D') == 'Sat') {
		                $closingDate = '15:00:00';
		            } else {
		                $closingDate = '16:00:00';
		            }

					break;

				case 'Move Item':

					if (date('D') == 'Sat') {
		                $closingDate = '15:00:00';
		            } else {
		                $closingDate = '16:00:00';
		            }

					break;

				case 'Update Stock':

					if (date('D') == 'Sat') {
		                $closingDate = '15:30:00';
		            } else {
		                $closingDate = '16:10:00';
		            }

					break;

				case 'Buku Besar':

					if (date('D') == 'Sat') {
		                $closingDate = '15:45:00';
		            } else {
		                $closingDate = '16:30:00';
		            }

					break;

			endswitch;

		} else {
			$closingDate = '23:59:59';
		}

		return $closingDate;
	}
	

	function romanic_number($integer)
	{
		// Convert the integer into an integer (just to make sure)
		$integer = intval($integer);
		$result = '';

		// Create a lookup array that contains all of the Roman numerals.
		$lookup = array('M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1);

		foreach($lookup as $roman => $value){
		// Determine the number of matches
		$matches = intval($integer/$value);

		// Add the same number of characters to the string
		$result .= str_repeat($roman,$matches);

		// Set the integer to be the remainder of the integer and the value
		$integer = $integer % $value;
		}

		// The Roman numeral should be built, return it
		return $result;
	}
