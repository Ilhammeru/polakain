
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
					echo 'Rp ' . number_format($receipt['nominal_bayar'], 2, ',', '.');
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

        <?php
        if (date('H:i:s') < $closingDate) { ?>
		<div class="row no-print">
			<div class="col-12">
				<div class="btn-group float-right">

					<?php 

					if ($this->session->userdata('p_approval_cancel') == 1) {

						$x = '<button type="button" onclick="cancel_approved(\'x\')" class="btn btn-danger">Cancel Approved</button>';
						$y = '<button type="button" onclick="cancel_approved(\'y\')" class="btn btn-danger">Cancel Approved DP</button>';
						$z = '<button type="button" onclick="cancel_approved(\'z\')" class="btn btn-danger">Cancel Approved Piutang</button>';
						$ly = '<button type="button" onclick="cancel_approved(\'ly\', \'' . $receipt['nominal_dp'] . '\')" class="btn btn-danger">Cancel Approved Lunas</button>';
						$lz = '<button type="button" onclick="cancel_approved(\'lz\')" class="btn btn-danger">Cancel Approved Lunas</button>';

						if ($receipt['sale_status'] == 1 && $receipt['date_dp'] != NULL && $receipt['payment_method_id'] != 'z') {

							echo $y;

						} elseif ($receipt['sale_status'] == 2 && $receipt['date_lunas'] != NULL && $receipt['date_dp'] == NULL && $receipt['date_piutang'] == NULL
							&& ($receipt['payment_method_id'] != 'z' || $receipt['payment_method_id'] != 0)) {

							echo $x;

						} elseif ($receipt['sale_status'] == 2 && $receipt['date_lunas'] != NULL 
							&& $receipt['date_dp'] != NULL) {

							echo $ly;

						} elseif ($receipt['sale_status'] == 2 && $receipt['date_lunas'] != NULL 
							&& $receipt['date_piutang'] != NULL) {

							echo $lz;

						} elseif ($receipt['sale_status'] == 3 && $receipt['due_date'] != NULL) {

							echo $z;

						}

					}

					?>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
	<!-- /.invoice -->


	<script>

		$(document).ready( function () {

			load_detail();

		});	

		function insert_row(data, last) {
			var tableSales = document.getElementById('table-receipt');

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
					cellT3.innerHTML = '<div class="text-center"><b>' + number_format(data.template_price) + '</b></div>';
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
			cell4.innerHTML = '<div class="text-center">' + number_format(data.price) + '</div>';
			cell5.innerHTML = '<div class="text-right">' + number_format(data.total) + '</div>';

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

		function cancel_approved(key, nominal_dp = 0) {

			Swal.fire({
                title: 'Cancel approved?',
                //text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya!'
                }).then((result) => {

                if (result.isConfirmed) {

					$.ajax({
						url: "<?=site_url('acc/cancel_approved');?>",
						type: "post",
						data: {
							id: "<?php echo $receipt['id'];?>",
							key: key,
							nominal_dp: nominal_dp
						},
						success: function (response) {

							if (response == 'success') {

			            		Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Data berhasil disimpan',
									showConfirmButton: false,
									timer: 1500
								});

								setTimeout(function(){
                                	location.href = "<?=site_url('acc/detail/' . $receipt['id']);?>";
                                }, 2000);

			            	} else if (response == 'error') {

			            		Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Ada kesalahan!',
								});

			            	}

						}
					});

				}

			});

		}

	</script>