
	<!-- Main content -->
	<div class="invoice p-3 mb-3">

		<div class="ribbon-wrapper ribbon-lg">
			<?php
			if ($receipt['sale_status'] == 1) {

				echo '<div class="ribbon bg-warning">DP</div>';

			} elseif ($receipt['sale_status'] == 2) {

				echo '<div class="ribbon bg-success">LUNAS</div>';

			} elseif ($receipt['sale_status'] == 3) {

				echo '<div class="ribbon bg-danger">PIUTANG</div>';

			}
			?>
		</div>

		<!-- title row -->
		<div class="row">

			<div class="col-12">
			<h4>
			<i class="fa fa-hashtag"></i> <?php echo $receipt['receipt_number'];?>
			<small><?php echo date_format(date_create($receipt['created_time']), 'd M Y');?></small>
			</h4>
			</div>
			<!-- /.col -->

		</div>
		<!-- info row -->

		<div class="row invoice-info">

			<div class="col-sm-4 invoice-col">
				Kepada
				<address>
					<strong><?php echo $receipt['customer_name'];?></strong><br>
				</address>
			</div>
			<!-- /.col -->

			<div class="col-sm-4 invoice-col">
			</div>
			<!-- /.col -->

			<div class="col-sm-4 invoice-col">
			
			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">

			<div class="col-12 table-responsive">

            	<table id="table-receipt" class="table table-stripted table-valign-middle">

            		<head>
            			<tr class="text-center">
            				<th style="width: 15%">Kode</th>
            				<th style="width: 30%">Barang</th>
            				<th style="width: 15%">Qty</th>
            				<th style="width: 17%">Harga</th>
            				<th style="width: 18%">Jumlah</th>
            			</tr>
            		</head>

            		<tbody>
            		</tbody>

            	</table>

			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<div class="row">
		<!-- accepted payments column -->
		
			<div class="col-6">
				<p class="lead">Status:</p>
				<!--
				<img src="../../dist/img/credit/visa.png" alt="Visa">
				<img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
				<img src="../../dist/img/credit/american-express.png" alt="American Express">
				<img src="../../dist/img/credit/paypal2.png" alt="Paypal">-->
			
				<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
					<?php 

					if ($receipt['date_kirim'] != NULL) {
						$badge_kirim = '<div class="badge bg-purple">Dikirim</div>';
					} else {
						$badge_kirim = '';
					}

					if (($receipt['sale_status'] == 1 && $receipt['payment_method_id'] == 'z') || ($receipt['sale_status'] == 1 && $receipt['date_dp'] != NULL)) {

						echo '<div class="badge badge-warning">DP</div> <div class="badge badge-primary">Approved</div>';

					} elseif ($receipt['sale_status'] == 1 && $receipt['date_dp'] == NULL) {

						echo '<div class="badge badge-warning">DP</div> <div class="badge badge-secondary">Menunggu Approval</div>';

					} elseif ($receipt['sale_status'] == 2 && $receipt['date_lunas'] == NULL) {

						echo '<div class="badge badge-success">Lunas</div> <div class="badge badge-secondary">Menunggu Approval</div>';
						
					} elseif ($receipt['sale_status'] == 2 && $receipt['date_lunas'] != NULL) {

						echo '<div class="badge badge-success">Lunas</div> <div class="badge badge-primary">Approved</div> ' . $badge_kirim;

					} elseif ($receipt['sale_status'] == 3 && $receipt['due_date'] == NULL) {

						echo '<div class="badge badge-danger">Piutang</div> <div class="badge badge-secondary">Menunggu Approval</div>';

					} elseif ($receipt['sale_status'] == 3 && $receipt['due_date'] != NULL) {

						echo '<div class="badge badge-danger">Piutang</div> <div class="badge badge-primary">Approved</div> ' . $badge_kirim;

					}
					?>
				</p>

				<?php if($receipt['sale_status'] != 3) {?>
				<p class="lead">Metode Pembayaran:</p>
				<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
					<?php echo $receipt['payment_method_name']; ?>
				</p>
				<?php } ?>
				<?php if ($receipt['sale_status'] == 1) {
					echo '<p class="lead">Nominal Bayar:</p>';
					echo '<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">';
					echo 'Rp ' . number_format($receipt['nominal_bayar'], 2, '.', ',');
					echo '</p>';
				} ?>
				
				<?php if ($receipt['sale_status'] == 1) {

					echo '<p class="lead">DP Tanggal:</p>';
					echo '<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">' . date_format(date_create($receipt['date_pay']), 'd M Y H:i:s') . '</p>';

				} elseif ($receipt['sale_status'] == 2) {

					echo '<p class="lead">Dibayar Tanggal:</p>';
					echo '<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">' . date_format(date_create($receipt['date_pay']), 'd M Y H:i:s') . '</p>';

				} elseif ($receipt['sale_status'] == 3) {

					if ($receipt['due_date'] != '') {
						echo '<p class="lead">Jatuh Tempo:</p>';
						echo '<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">';
						if ($receipt['datediff'] == 0) {
							echo '<div class="badge badge-danger">Hari ini</div>';
						} elseif ($receipt['datediff'] == 1) {
							echo '<div class="badge badge-danger">Besok</div>';
						} elseif ($receipt['datediff'] == 2) {
							echo '<div class="badge badge-danger">Lusa</div>';
						} else {
							echo $receipt['datediff'] . ' Hari lagi';
						}
						echo '</p>';
					}
				}		
				?>
				
			</div>
			<!-- /.col -->

			<div class="col-6">

				<div class="table-responsive">
					<table class="table table-valign-middle">
						<tr>
							<th style="width:50%">Subtotal</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($receipt['total_price'], 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>PPN (10%)</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($receipt['ppn'], 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Ongkir</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($receipt['d_cost'], 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Total</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($receipt['grand_total'], 2, '.', ',');?></td>
						</tr>
					</table>
				</div>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<!-- this row will not appear when printing --><!-- 
		<div class="row no-print">
			<div class="col-12">
				<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
				<button type="button" class="btn btn-success float-right">
					<i class="far fa-credit-card"></i> Submit Payment
				</button>
				<button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
					<i class="fas fa-download"></i> Generate PDF
				</button>
			</div>
		</div> -->

		<?php 
		if (date('H:i:s') > $closingDate) {

		} else {

			if ($this->session->userdata('p_sale_edit') == 1) {
				// $x = '<div class="row no-print">
				// 	<div class="col-12">
				// 		<a href="' . site_url('sales/edit/' . $receipt['id']) . '" target="_blank" class="btn btn-primary float-right">Edit</a>
				// 	</div>
				// </div>';
				$x = '';
			} else {
				$x = '';
			}

			// Lunas + Tunai

			if ($receipt['date_kirim'] == NULL) {

				if ($receipt['sale_status'] == 2 && $receipt['payment_method_id'] == 'z' && $receipt['date_lunas'] != NULL
					&& $receipt['date_dp'] == NULL & $receipt['date_piutang'] == NULL) {
					echo $x;
				} elseif ($receipt['sale_status'] == 2 && $receipt['payment_method_id'] != 'z' && $receipt['date_lunas'] == NULL) {
					echo $x;
				} elseif ($receipt['sale_status'] == 1) {
					echo $x;
				}  elseif ($receipt['sale_status'] == 3) {
					echo $x;
				} 

			}

		}

		?>

	</div>
	<!-- /.invoice -->


	<script>

		$(document).ready( function () {

			load_detail();

		});	

		function insert_row(data, last) {
			var tableSales = document.getElementById('table-receipt');
			var template_price_txt = number_format(data.template_price);

			if (data.template_id != 0) {
				template_price_txt = '-';
			}

			if (last != data.template_name) {

				var rowT = tableSales.insertRow(1);
				var cellT1 = rowT.insertCell(0);
				cellT1.colSpan = 2;
				var cellT2 = rowT.insertCell(1);
				var cellT3 = rowT.insertCell(2);
				var cellT4 = rowT.insertCell(3);
				cellT1.innerHTML = '<b>' + data.template_name + '</b>';
				cellT2.innerHTML = '<div class="text-center"><b>' + data.template_qty + '</b></div>';

				if (data.template_price != '') {
					cellT3.innerHTML = '<div class="text-center"><b>' + template_price_txt + '</b></div>';
					cellT4.innerHTML = '<div class="text-right"><b>' + number_format(data.template_qty*data.template_price) + '</b></div>';
				}
			}

			var row = tableSales.insertRow(2);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);

			cell1.innerHTML = '<div class="text-center">' + data.code + '</div>';
			cell2.innerHTML = data.name;
			cell3.innerHTML = '<div class="text-center">' + data.qty + '</div>';

			if (data.template_id  == 0) {
				var price_txt = number_format(data.price);
				var total_txt = number_format(data.total);
			} else {
				var price_txt = '-';
				var total_txt = '-';
			}

			cell4.innerHTML = '<div class="text-center">' + price_txt + '</div>';
			cell5.innerHTML = '<div class="text-right">' + total_txt + '</div>';

		}

		function load_detail() {

			$.ajax({
				url: "<?=site_url('sales/server_side_data_detail');?>",
				type: "get",
				data: {
					id: "<?php echo $receipt['id'];?>"
				},
				dataType: "json",
				success: function (data) {

					var last = null;
					for (i = 0; i < data.length; i++) {
						insert_row(data[i], last);
						last = data[i].template_name;
					}

				}
			})

		}

	</script>