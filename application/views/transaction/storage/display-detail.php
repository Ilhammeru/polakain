
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

		<?php

		if ($x == 1) { ?>

			<div class="ribbon-wrapper ribbon-xl">
				<div class="ribbon bg-purple">DIKIRIM</div>
			</div>	

		<?php } ?>

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
				<?php 

				if ($x == 1) {

					echo 'Dikirim tanggal<br>';
					echo '<b>' . date_format(date_create($receipt['date_kirim']), 'd M Y H:i:s') . '</b>';

				} ?>
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
            			</tr>
            		</head>

            		<tbody>
            		</tbody>

            	</table>

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
		
		<div class="row no-print">
			<div class="col-12">
				<?php 

				if ($x == 0) {	
					if ($this->session->userdata('p_storage_approval') == 1) { 
						// Colomadu (Polakain)
						if ($this->session->userdata('p_warehouse_id') == 4)  {
							echo '<a href="javascript:void(0)" id="btn-send-warehouse" class="btn btn-primary float-right">Kirim Gudang</a>';
						} else {
							echo '<a href="javascript:void(0)" id="btn-send" class="btn btn-primary float-right">Kirim</a>';
						}
					}
				} else { 
				 	if ($this->session->userdata('p_storage_cancel') == 1) { ?>
				 		<div class="btn-group">
				 			<?php if (date('H:i:s') < $closingDate) { ?>
							<a href="javascript:void(0)" id="btn-cancel" class="btn bg-purple float-right">Cancel Kirim</a>
							<?php } ?>
							<a href="<?=site_url('sales/print/' . $receipt['id']);?>" target="_blank" id="btn-print" class="btn btn-primary float-right">Print</a>
						</div>
					<?php }
				} ?>
			</div>
		</div>

	</div>
	<!-- /.invoice -->


	<script>

		$(document).ready( function () {

			load_detail();

			$('#btn-send').on('click', function () {

				var id = "<?php echo $receipt['id'];?>";
				var nominal = "<?php echo number_format($receipt['total_price'], 0, '.', ',');?>";
				var d_cost = "<?php echo number_format($receipt['d_cost'], 0, '.', ',');?>";
				var ppn = "<?php echo number_format($receipt['ppn'], 0, '.', ',');?>";

				Swal.fire({
	                title: 'Anda yakin sudah selesai packing?',
	                //text: "You won't be able to revert this!",
	                icon: 'warning',
	                showCancelButton: true,
	                confirmButtonColor: '#007bff',
	                cancelButtonColor: '#6c757d',
	                confirmButtonText: 'Kirim!'
	                }).then((result) => {

	                if (result.isConfirmed) {

						$.ajax({
							url: "<?=site_url('storage/save_data');?>",
							data: {
								id : id,
								nominal : nominal,
								ppn : ppn,
								d_cost : d_cost
							},
							type: "post",
							success: function(response) {

								if (response == 'success') {

			                        Swal.fire({
			                            position: 'center',
			                            icon: 'success',
			                            title: 'Data berhasil disimpan',
			                            showConfirmButton: false,
			                            timer: 1500
			                        });

			                        setTimeout(function(){

			                        	$('#table-list-packing').DataTable().ajax.reload();
			                        	$('#table-history').DataTable().ajax.reload();

			                        	$('#display-detail').html('');

			                        	window.open("<?=site_url('sales/print/' . $receipt['id']);?>", "_blank");

			                        }, 1500);

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

			});

			$('#btn-cancel').on('click', function () {

				var id = "<?php echo $receipt['id'];?>";

				Swal.fire({
	                title: 'Cancel kirim?',
	                //text: "You won't be able to revert this!",
	                icon: 'warning',
	                showCancelButton: true,
	                confirmButtonColor: '#007bff',
	                cancelButtonColor: '#6c757d',
	                confirmButtonText: 'Ya!'
	                }).then((result) => {

	                if (result.isConfirmed) {

						$.ajax({
							url: "<?=site_url('storage/cancel_kirim');?>",
							data: {
								id : id
							},
							type: "post",
							success: function(response) {

								if (response == 'success') {

			                        Swal.fire({
			                            position: 'center',
			                            icon: 'success',
			                            title: 'Data berhasil disimpan',
			                            showConfirmButton: false,
			                            timer: 1500
			                        });

			                        setTimeout(function(){

			                        	$('#table-list-packing').DataTable().ajax.reload();
			                        	$('#table-history').DataTable().ajax.reload();

			                        	$('#display-detail').html('');

			                        }, 1500);

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

			});

		});	

		$('#btn-send-warehouse').on('click', function () {
			var id = "<?php echo $receipt['id'];?>";

			Swal.fire({
				title: 'Anda yakin sudah selesai packing?',
				//text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#007bff',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Kirim!'
				}).then((result) => {

				if (result.isConfirmed) {

					$.ajax({
						url: "<?=site_url('storage/move_storage_with_inv');?>",
						data: {
							id : id
						},
						type: "post",
						success: function(response) {

							if  (response == 'error') {

								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Ada kesalahan!',
								});
							} else {

								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Data berhasil disimpan',
									showConfirmButton: false,
									timer: 1500
								});

								setTimeout( function () {
									location.href = '<?=site_url('storage/packing_list');?>';
								}, 1500);
							}
						}
					});
				}
			});
		});

		function insert_row(data, last) {
			var tableSales = document.getElementById('table-receipt');

			if (last != data.template_name) {
				var rowT = tableSales.insertRow(1);
				var cellT1 = rowT.insertCell(0);
				cellT1.colSpan = 2;
				var cellT2 = rowT.insertCell(1);
				cellT1.innerHTML = '<b>' + data.template_name + '</b>'; 
				cellT2.innerHTML = '<div class="text-center"><b>' + data.template_qty + '</b></div>';
			}

			var row = tableSales.insertRow(2);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);

			cell1.innerHTML = '<div class="text-center">' + data.code + '</div>';
			cell2.innerHTML = data.name;
			cell3.innerHTML = '<div class="text-center">' + data.qty + '</div>';

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