<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Cron
 */
class Cron extends CI_Controller {

	public function validate() {

		$attr = array(
						'department' => $this->db->query("SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id 
																 UNION ALL 
																 SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL")->result()
					);

        $this->load->view('attend/layouts/header');
		$this->load->view('attend/layouts/navbar');
		$this->load->view('attend/validate/navbar-sub');
		$this->load->view('attend/layouts/content-wrapper');
		$this->load->view('attend/validate/index', $attr);
		$this->load->view('attend/layouts/footer');

	}

	public function validate_data() {

		$date = $this->input->post('date');
		$ref_id = $this->input->post('ref_id');

		$custom = $this->db->query("SELECT JSON_EXTRACT(detail, '$." . $date . "') AS 'time_custom'
															FROM attend_custom
															WHERE ref_id = '" . $ref_id . "'
															AND JSON_EXTRACT(detail, '$." . $date . "')");

		// Custom jam masuk kerja
		if ($custom->num_rows() > 0) {

			$custom = $custom->row_array();

			// if time custom
			if (! empty($custom['time_custom']) || $custom['time_custom'] != '') {
				$workingTimeFinal = str_replace('"', '', $custom['time_custom']);

				if (substr($ref_id, 0, 1) == 'd') {

					$key = preg_replace('/[^0-9]/', '', $ref_id);

					$this->db->query("UPDATE ansena_attend 
											JOIN ac_payroll_item ON ac_payroll_item.id = ansena_attend.ac_payroll_item_id
											SET attend_status = 'OK'
											WHERE is_active = 1
											AND DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $date . "'
											AND DATE_FORMAT(time_scan, '%H:%i:%s') <= '" . $workingTimeFinal . "'
											AND attend_status = 'TERLAMBAT'
											AND office = " . $key);
				
				} elseif (substr($ref_id, 0, 1) == 't') {

					$key = preg_replace('/[^0-9]/', '', $ref_id);

					$this->db->query("UPDATE ansena_attend 
											JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ansena_attend.ac_payroll_item_id
											SET attend_status = 'OK'
											WHERE DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $date . "'
											AND DATE_FORMAT(time_scan, '%H:%i:%s') <= '" . $workingTimeFinal . "'
											AND attend_status = 'TERLAMBAT'
											AND ansena_team_id = " . $key);

				}

			}
		}
		
		$this->attend_summary($date);
	}
	// End of function validate_data

	public function validate_data_bac() {

		$date = $this->input->post('date');
		$ref_id = $this->input->post('ref_id');

		$custom = $this->db->query("SELECT JSON_EXTRACT(detail, '$." . $date . "') AS 'time_custom'
															FROM attend_custom
															WHERE ref_id = '" . $ref_id . "'
															AND JSON_EXTRACT(detail, '$." . $date . "')");

		// Custom jam masuk kerja
		if ($custom->num_rows() > 0) {

			$custom = $custom->row_array();

			// if time custom
			if (! empty($custom['time_custom']) || $custom['time_custom'] != '') {
				$workingTimeFinal = str_replace('"', '', $custom['time_custom']);

				if (substr($ref_id, 0, 1) == 'd') {

					$key = preg_replace('/[^0-9]/', '', $ref_id);

					$this->db->query("UPDATE ansena_attend 
											JOIN ac_payroll_item ON ac_payroll_item.id = ansena_attend.ac_payroll_item_id
											SET attend_status = 'OK'
											WHERE is_active = 1
											AND DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $date . "'
											AND DATE_FORMAT(time_scan, '%H:%i:%s') <= '" . $workingTimeFinal . "'
											AND attend_status = 'TERLAMBAT'
											AND office = " . $key);
				
				} elseif (substr($ref_id, 0, 1) == 't') {

					$key = preg_replace('/[^0-9]/', '', $ref_id);

					$this->db->query("UPDATE ansena_attend 
											JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ansena_attend.ac_payroll_item_id
											SET attend_status = 'OK'
											WHERE DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $date . "'
											AND DATE_FORMAT(time_scan, '%H:%i:%s') <= '" . $workingTimeFinal . "'
											AND attend_status = 'TERLAMBAT'
											AND ansena_team_id = " . $key);

				}

			}
		}

		//Query jam masuk kerja setelah event
		$custom_event = $this->db->query("SELECT time_attend, events.detail
											FROM attend_custom 
											JOIN events ON events.id = SUBSTRING(ref_id, 2)
											WHERE SUBSTRING(ref_id, 1, 1) = 'x'
											AND DATE_FORMAT(attend_custom.created_time, '%Y-%m-%d') = '" . $date . "'");

		if ($custom_event->num_rows() > 0) {

			$custom_event = $custom_event->result();

			foreach ($custom_event as $row) {

				$detail = json_decode($row->detail, TRUE);

				if (isset($detail[$ref_id])) {

					$workingTimeFinal = $row->time_attend;

					if (substr($ref_id, 0, 1) == 'd') {

						$key = preg_replace('/[^0-9]/', '', $ref_id);

						$this->db->query("UPDATE ansena_attend 
												JOIN ac_payroll_item ON ac_payroll_item.id = ansena_attend.ac_payroll_item_id
												JOIN events_attend ON events_attend.ac_payroll_item_id = ansena_attend.ac_payroll_item_id
												SET attend_status = 'OK'
												WHERE is_active = 1
												AND DATE_FORMAT(ansena_attend.time_scan, '%Y-%m-%d') = '" . $date . "'
												AND DATE_FORMAT(ansena_attend.time_scan, '%H:%i:%s') <= '" . $workingTimeFinal . "'
												AND attend_status = 'TERLAMBAT'
												AND office = " . $key);

					} elseif (substr($ref_id, 0, 1) == 't') {

						$key = preg_replace('/[^0-9]/', '', $ref_id);

						$this->db->query("UPDATE ansena_attend 
												JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ansena_attend.ac_payroll_item_id
												JOIN events_attend ON events_attend.ac_payroll_item_id = ansena_attend.ac_payroll_item_id
												SET attend_status = 'OK'
												WHERE DATE_FORMAT(ansena_attend.time_scan, '%Y-%m-%d') = '" . $date . "'
												AND DATE_FORMAT(ansena_attend.time_scan, '%H:%i:%s') <= '" . $workingTimeFinal . "'
												AND attend_status = 'TERLAMBAT'
												AND ansena_team_id = " . $key);

					}

				}

			}

		}
		
		$this->attend_summary($date);
	}
	// End of function validate_data

	public function attend_summary($data_date = false) {

		if ($data_date != false) {
			$date = $data_date;
		} else {
			$date = date('Y-m-d');
		}

		$result_in = $this->db->query("SELECT ac_payroll_item_id, 
											MIN(DATE_FORMAT(time_scan, '%H:%i:%s')) AS 'time_in',
											MIN(CONCAT(DATE_FORMAT(time_scan, '%H:%i:%s'), '-', attend_status)) AS 'x',
											MIN(CONCAT(DATE_FORMAT(time_scan, '%H:%i:%s'), '-', mood)) AS 'x_mood',
											hrd_act
											FROM ansena_attend
											WHERE DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $date . "'
											AND attend != 'OUT'
											AND attend_status != 'ISTIRAHAT'
											GROUP BY ac_payroll_item_id")->result();

		$result_out = $this->db->query("SELECT ac_payroll_item_id, 
											MAX(DATE_FORMAT(time_scan, '%H:%i:%s')) AS 'time_out',
											MAX(CONCAT(DATE_FORMAT(time_scan, '%H:%i:%s'), '-', mood)) AS 'x_mood'
											FROM ansena_attend
											WHERE DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $date . "'
											AND attend = 'OUT'
											AND attend_status != 'ISTIRAHAT'
											GROUP BY ac_payroll_item_id")->result();

		$data = array();

		foreach ($result_in as $row) :

			$explode = explode('-', $row->x);
			$attend_status = $explode[1];

			$day = date('D', strtotime($date));

			// if ($day == 'Sun') {
			// 	$attend_status = 'OK';
			// }

			$mood = null;
			if ($row->x_mood != null) {

				$explode_mood = explode('-', $row->x_mood);
				$mood = $explode_mood[1];
			}

			$data['i'.$row->ac_payroll_item_id]['time_in'] 			= $row->time_in;
			$data['i'.$row->ac_payroll_item_id]['attend_status'] 	= $attend_status;
			$data['i'.$row->ac_payroll_item_id]['hrd_act']			= $row->hrd_act;
			$data['i'.$row->ac_payroll_item_id]['mood_in']				= $mood;

		endforeach;

		foreach ($result_out as $row) :

			$mood = null;
			if ($row->x_mood != null) {

				$explode_mood = explode('-', $row->x_mood);
				$mood = $explode_mood[1];
			}

			$data['i'.$row->ac_payroll_item_id]['time_out'] 		= $row->time_out;
			$data['i'.$row->ac_payroll_item_id]['mood_out']				= $mood;

		endforeach;

		$dataInsert = array(
							'attend_date' => $date,
							'detail' => json_encode($data)
						);

		$attend = $this->db->query("SELECT id FROM ansena_attend_resume WHERE attend_date = '" . $date . "'")->num_rows();

		if ($attend > 0) {
			$this->db->where('attend_date', $date);
			$this->db->delete('ansena_attend_resume');
		}
		$this->db->insert('ansena_attend_resume', $dataInsert);
		
	}
	// End of function attend_summary

	public function x() {

		$x = $this->db->query("SELECT DISTINCT(DATE_FORMAT(time_scan, '%Y-%m-%d')) AS x FROM ansena_attend")->result();

		foreach ($x as $y) {

			$result = $this->db->query("SELECT ac_payroll_item_id, 
										   MIN(DATE_FORMAT(time_scan, '%H:%i:%s')) AS 'time_in',
										   MAX(DATE_FORMAT(time_scan, '%H:%i:%s')) AS 'lasted',
										   IF(attend = 'IN', attend_status, NULL) AS 'attend_status',
										   IF(attend = 'IN', hrd_act, 0) AS 'hrd_act'
										   FROM ansena_attend
										   WHERE DATE_FORMAT(time_scan, '%Y-%m-%d') = '" . $y->x . "'
										   GROUP BY ac_payroll_item_id")->result();

			$data = array();

			foreach ($result as $row) :

				$data['i'.$row->ac_payroll_item_id] = array(
															'time_in' => $row->time_in,
															'time_out' => $row->lasted,
															'attend_status' => $row->attend_status,
															'hrd_act' => $row->hrd_act
														);

			endforeach;

			$dataInsert = array(
								'attend_date' => $y->x,
								'detail' => json_encode($data)
							);

			$this->db->insert('ansena_attend_resume', $dataInsert);

		}
		
	}
	// End of function x

	public function data_x() {
		
		$result_out = $this->db->query("SELECT ansena_attend.ac_payroll_item_id, 
											ac_payroll_item.name,
											DATE_FORMAT(time_scan, '%Y-%m-%d') AS 'date',
											MAX(DATE_FORMAT(time_scan, '%H:%i:%s')) AS 'time_out'
											FROM ansena_attend
											JOIN ac_payroll_item ON ac_payroll_item.id = ansena_attend.ac_payroll_item_id
											JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ansena_attend.ac_payroll_item_id
											WHERE time_scan >= '2020-12-01 00:00:00'
											AND time_scan <= '2020-12-18 00:00:00'
											AND attend_status != 'ISTIRAHAT'
											AND ansena_team_detail.ansena_team_id = 5
											GROUP BY ac_payroll_item_id, DATE_FORMAT(time_scan, '%Y-%m-%d')
											ORDER BY time_scan, ac_payroll_item.name")->result();

		$html = '<table style="border:1px solid black">';

		foreach ($result_out as $row) :

			$html .= '<tr style="border:1px solid black">';
			$html .= '<td style="border:1px solid black">' . $row->name . '</td>';
			$html .= '<td style="border:1px solid black">' . $row->date . '</td>';
			$html .= '<td style="border:1px solid black">' . $row->time_out . '</td>';
			$html .= '</tr>';

		endforeach;

		$html .= '</table>';

		echo $html;

	}
	// End of function data_x

}
/* End of file Cron.php */
/* Location: ./application/controllers/Cron.php */
