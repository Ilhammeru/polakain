	


	<style>
		.text-right {
			text-align: right;
		}
		.text-center {
			text-align: center;
		}
		table {
			width: 90% !important;
			font-size: 18px !important;
		}
		.border-dash {
			border-top: 1px dashed black !important;
			border-bottom: 1px dashed black !important;
		}
		.border-dash-top {
			border-top: 1px dashed black !important;
		}
		.border-dash-bottom {
			border-bottom: 1px dashed black !important;
		}
		@media print
		{
			table {
				font-size: 3vw;
				font-family: arial;
			}
			@page {
				size: 48mm 3276mm;
				size: portrait;
			}
		}
	</style>

	<?php

	$dataItem = array();
	$dataTemplate = array();

	foreach ($item as $row) {
		$dataItem['i' . $row->id] = $row->name;
	}

	foreach ($template_item as $row) {
		$dataTemplate['i' . $row->id] = $row->template_name;
	}

	$data_detail = json_decode($sales['data_detail'], TRUE);

	echo '<table>';
	echo '<tr>';
	echo '<td colspan="3" class="text-center" style="font-size:20px"><b>' . $sales['fullname'] . '</b></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="3">';
	if ($data_detail['address'] != '') { 
		$address = explode(',x,', $data_detail['address']);
		for ($i = 0; $i < count($address); $i++) {
			echo $address[$i] . '<br>'; 
		}
	}
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="3"><b>Customer</b></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td coslpan="3">' . $sales['customer_ref'] . '</td>';
	echo '</tr>';
	// echo '<tr>';
	// echo '<td colspan="3">========================================</td>';
	// echo '</tr>';
	echo '<tr>';
	echo '<td colspan="3" class="border-dash">' . $sales['receipt_number'] . '</td>';
	echo '</tr>';

	// echo '<tr>';
	// echo '<td colspan="3">========================================</td>';
	// echo '</tr>';

	$arrayData = json_decode($sales['detail']);

	if (count($arrayData) > 0) {

		$last_t = null;
		foreach ($arrayData as $value) {

			$key = 'i' . $value->item_id;

			$item_id = $value->item_id;
			$template_id = 'i' . $value->template_id;
			$qty = $value->qty;
			$price = $value->price;
			$total = $qty * $price;


			if (isset($dataItem[$key])) {
				$name = $dataItem[$key];
			} else {
				$name = '???';
			}

			if (isset($dataTemplate[$template_id])) {
				$template_name = $dataTemplate[$template_id];

				if(isset($value->template_qty)) {
					$template_qty = $value->template_qty;
				} else {
					$template_qty = "";
				}
				if (isset($value->template_price)) {
					$template_totalprice = number_format($value->template_qty * $value->template_price);
				} else {
					$template_totalprice = "";
				}

			} else {
				$template_name = '???';
				$template_qty = '???';
				$template_totalprice = 0;
			}

			if ($template_name != '???') {

				if ($value->template_id != $last_t) {
					echo '<tr>';
					echo '<td colspan="2"><b>Paket: ' . $template_name . ' (' . $template_qty . ')</b></td>';
					echo '<td class="text-right"><b>' . $template_totalprice . '</b></td>';
					echo '</tr>';
				}

				echo '<tr>';
				echo '<td colspan="3" style="text-indent:20px">' . $name . '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="text-right" style="width: 15%">' . $qty . '</td>';
				echo '<td class="text-right" style="width: 35%">' . number_format($price) . '</td>';
				echo '<td class="text-right" style="width: 50%">' . number_format($total) . '</td>';
				echo '</tr>';

			} else {

				echo '<tr>';
				echo '<td colspan="3">' . $name . '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="text-right" style="width: 15%">' . $qty . '</td>';
				echo '<td class="text-right" style="width: 35%">' . number_format($price) . '</td>';
				echo '<td class="text-right" style="width: 50%">' . number_format($total) . '</td>';
				echo '</tr>';

			}

			$last_t = $value->template_id;

		}

	}

	echo '<tr>';
	echo '<td class="border-dash-top">Total</td>';
	echo '<td class="border-dash-top"></td>';
	echo '<td class="text-right border-dash-top">' . number_format($sales['total_price']) . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2">PPN</td>';
	echo '<td class="text-right">' . number_format($sales['ppn']) .'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2">Ongkos Kirim</td>';
	echo '<td class="text-right">' . number_format($sales['d_cost']) .'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2">Total</td>';
	echo '<td class="text-right">' . number_format($sales['grand_total']) .'</td>';
	echo '</tr>';

	// echo '<tr>';
	// echo '<td colspan="3">========================================</td>';
	// echo '</tr>';

	echo '<tr>';
	echo '<td colspan="3" class="border-dash">Tgl. ' . date_format(date_create($sales['date_kirim']), 'd-m-Y H:i:s') .'</td>';
	echo '</tr>';

	// echo '<tr>';
	// echo '<td colspan="3">========================================</td>';
	// echo '</tr>';

	echo '</table>';

	?>