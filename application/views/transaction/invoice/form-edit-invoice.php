	
	<style>

		.dataTables_filter {
            display: block;
            padding: 15px; 
        }

	</style>

	<form id="form-barang-datang-edit" method="post" action="<?=site_url('invoice/update_barang_datang');?>">
	<div class="row">

		<div class="col-md-10 offset-md-1">

			<div class="card card-primary">
				
				<div class="card-header border-0">
					<h3 class="card-title">Edit Barang Datang</h3>
				</div>

				<div class="card-body">

					<div class="row">

						<input type="hidden" name="grandtotal" id="grandtotal" value="<?=$invoice['total_price'];?>">

						<div class="col-sm-3">

							<div class="form-group">

								<label for="invoice_number">Nomor Invoice</label>
								<input type="text" id="invoice_number" name="invoice_number"
									   class="form-control" value="<?=$invoice['invoice_number'];?>">

							</div>

						</div>

						<div class="col-md-4">

							<div class="form-group">

								<label for="vendor_id">Vendor</label>
								<select id="vendor_id" name="vendor_id"
									    class="form-control">
										<option value="<?=$invoice['vendor_id'];?>" selected><?=$invoice['vendor_name'];?></option>
										<?php
										foreach ($vendor as $row):
											echo '<option value="' . $row->id . '">' . $row->name . '</option>';
										endforeach;
										?>
								</select>

							</div>

						</div>
						<!-- /div.col -->

						<div class="col-md-3">

							<div class="form-group">

								<label for="d_cost">Diskon</label>
								<input type="text" id="disc" name="disc"
									   class="form-control currency-rp" value="<?php echo $invoice['disc'];?>">

							</div>

						</div>
						<!-- /div.col -->

						<div class="col-sm-2">

							<div class="form-group">

								<label for="ppn">PPN</label>
								<input type="text" id="ppn" name="ppn"
									   class="form-control currency-rp" value="<?php echo $invoice['ppn'];?>">

							</div>

						</div>
						<!-- /div.col -->

					</div>
					<!-- /div.row -->

					<div class="row">

						<div class="col-sm-3">

							<div class="form-group">

								<label for="d_cost">Ongkos Kirim</label>
								<input type="text" id="d_cost" name="d_cost"
									   class="form-control currency-rp" value="<?php echo $invoice['d_cost'];?>">

							</div>

						</div>
						<!-- /div.col -->

						<div clsss="col-md-3">

							<div class="form-group">

								<label for="ekpedisi_matoa">Ekspedisi Matoa</label>
								<br>
            					<div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%">
            						<?php

            						if ($invoice['with_matoa_shipping'] == 0) {?>
										<input type="radio" name="with_matoa_shipping" value="0" autocomplete="off" checked> 
	                          			<label class="form-check-label">Tidak</label>
										<input type="radio" name="with_matoa_shipping" value="1" autocomplete="off">
	                          			<label class="form-check-label">Ya</label>
									<?php } else { ?>
										<input type="radio" name="with_matoa_shipping" value="0" autocomplete="off"> 
	                          			<label class="form-check-label">Tidak</label>
										<input type="radio" name="with_matoa_shipping" value="1" autocomplete="off" checked>
	                          			<label class="form-check-label">Ya</label>
									<?php } ?>

								</div>

							</div>

						</div>
						<!-- /div.col -->

						<div class="col-sm-1">

						</div>

						<div clsss="col-sm-3">

							<div class="form-group">
								<label for="pembayaran">Pembayaran</label>

								<div class="form-check">
									<?php

									if ($invoice['payment_status'] == 2) { ?>
										<input type="radio" name="payment_status" value="2" autocomplete="off" checked> 
	                          			<label class="form-check-label">Lunas</label>
										<input type="radio" name="payment_status" value="3" autocomplete="off">
	                          			<label class="form-check-label">Hutang</label>
	                          		<?php } else { ?>
										<input type="radio" name="payment_status" value="2" autocomplete="off"> 
	                          			<label class="form-check-label">Lunas</label>
										<input type="radio" name="payment_status" value="3" autocomplete="off" checked>
	                          			<label class="form-check-label">Hutang</label>
	                          		<?php } ?>
                        		</div>

							</div>

						</div>

					</div>
					<!-- /div.row -->

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<div class="row">

		<div class="col-md-10 offset-md-1">

			<div class="card">

				<div class="card-body p-0">

					<table id="tableListItem" class="table table-striped table-valign-middle m-0" style="width: 100%">

						<thead class="text-center">
							<tr>
								<th rowspan="2">Kode</th>
								<th rowspan="2">Nama Barang</th>
								<th colspan="<?php echo count($warehouse);?>">Gudang</th>
								<th rowspan="2">Harga</th>
								<th rowspan="2">Total Harga</th>
							</tr>
							<tr>
								<?php
								foreach ($warehouse as $row):
									echo '<th>' . $row->name . '</th>';
								endforeach;
								?>
							</tr>
						</thead>

						<tbody>
						</tbody>

					</table>

				</div>
				<!-- /div.card-body -->

				<div class="card-footer">
					<button type="submit" class="btn btn-primary float-right">Submit</button>
				</div>
				<!-- /div.card-footer -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->
	</form>

	<script>

		function calculate() {
			
			var total = 0;
			$('.totalprice').each( function () {
				var value = $(this).val();
				value = value.replace('.00','');
				value = value.replace(/,/g,'');
				total += +value;
			})

			$('#grandtotal').val(total);

		}

		$(document).ready( function () {

			var invoice_id = "<?php echo $invoice_id;?>";

			var tableListItem = $('#tableListItem').DataTable({
				// Data
                ajax: {
                    url: "<?=site_url('invoice/server_side_data_invoice_edit/' . $invoice_id);?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                ordering: false,
                scrollY: '400px',
                scrollCollapse: true

			});

			tableListItem.on('draw.dt', function () {
				currency_rp();
				calculate();
			});

			$('#form-barang-datang-edit').validate({

				rules: {
					invoice_number: {
						required: true
					},
					vendor_id: {
						required: true
					}
				},
				messages: {
					invoice_number: {
						required: 'Kolom nomor invoice harus diisi'
					},
					vendor_id: {
						required: 'Pilih vendor'
					}
				},
				errorClass: 'is-invalid',
				submitHandler: function (form) {

					Swal.fire({
	                    title: 'Data sudah benar?',
	                    //text: "You won't be able to revert this!",
	                    icon: 'warning',
	                    showCancelButton: true,
	                    confirmButtonColor: '#007bff',
	                    cancelButtonColor: '#6c757d',
	                    confirmButtonText: 'Submit!'
	                    }).then((result) => {

	                    if (result.isConfirmed) {

	                    	$.ajax({
					            url: form.action,
					            type: form.method,
					            data: $(form).serialize() + '&invoice_id=' + invoice_id,
					            success: function(response) {

					            	if (response == 'success') {

					            		setTimeout(function(){

											$(form)[0].reset();	

										}, 1000);

					            		Swal.fire({
											position: 'center',
											icon: 'success',
											title: 'Data berhasil disimpan',
											showConfirmButton: false,
											timer: 1500
										});

										setTimeout(function(){

											location.href = "<?=site_url('invoice/report_data');?>";

										}, 2000);

					            	} else if (response == 'error') {
					            		
					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} else if (response == 'error-null') {
					            		
					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Harga & total harga tidak boleh kosong!',
										});

					            	}

					            }

					        });

	                   	}
	                });

				}

			});

		});

	</script>


