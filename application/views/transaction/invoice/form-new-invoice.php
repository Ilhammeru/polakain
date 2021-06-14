

	<style>

		.dataTables_filter {
            display: block;
            padding: 15px; 
        }

	</style>

	<form id="form-barang-datang" method="post" action="<?=site_url('invoice/save_barang_datang');?>">
	<div class="row">

		<div class="col-md-10 offset-md-1">

			<div class="card card-primary">
				
				<div class="card-header border-0">
					<h3 class="card-title">Input Barang Datang</h3>
				</div>

				<div class="card-body">

					<div class="row">

						<input type="hidden" name="grandtotal" id="grandtotal">

						<div class="col-sm-3">

							<div class="form-group">

								<label for="invoice_number">Nomor Invoice</label>
								<input type="text" id="invoice_number" name="invoice_number"
									   class="form-control">

							</div>

						</div>

						<div class="col-md-4">

							<div class="form-group">

								<label for="vendor_id">Vendor</label>
								<select id="vendor_id" name="vendor_id"
									    class="form-control">
										<option value="" selected disabled>Pilih Vendor</option>
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
									   class="form-control currency-rp">

							</div>

						</div>
						<!-- /div.col -->

						<div class="col-sm-2">

							<div class="form-group">

								<label for="ppn">PPN</label>
								<input type="text" id="ppn" name="ppn"
									   class="form-control currency-rp">

							</div>

						</div>
						<!-- /div.col -->

					</div>

					<div class="row">

						<div class="col-sm-3">

							<div class="form-group">

								<label for="d_cost">Ongkos Kirim</label>
								<input type="text" id="d_cost" name="d_cost"
									   class="form-control currency-rp">

							</div>

						</div>
						<!-- /div.col -->

						<div clsss="col-sm-3">

							<div class="form-group">
								<label for="ekpedisi_matoa">Expedisi Matoa</label>

								<div class="form-check">
                          			<input type="radio" name="with_matoa_shipping" value="0" autocomplete="off" checked> 
                          			<label class="form-check-label">Tidak</label>
									<input type="radio" name="with_matoa_shipping" value="1" autocomplete="off">
                          			<label class="form-check-label">Ya</label>
                        		</div>

							</div>

						</div>

						<div class="col-sm-1">

						</div>

						<div clsss="col-sm-3">

							<div class="form-group">
								<label for="pembayaran">Pembayaran</label>

								<div class="form-check">
                          			<input type="radio" name="payment_status" value="2" autocomplete="off" checked> 
                          			<label class="form-check-label">Lunas</label>
									<input type="radio" name="payment_status" value="3" autocomplete="off">
                          			<label class="form-check-label">Hutang</label>
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
			});
			

			$('#grandtotal').val(total);

		}

		$(document).ready( function () {

            $('.currency-rp').inputmask("numeric", {

	            radixPoint: ".",
	            groupSeparator: ",",
	            digits: 2,
	            autoGroup: true,
	            prefix: 'Rp ',
	            rightAlign: false,
	            allowMinus: false,
	            oncleared: function () {
	                self.value('');
	            }

	        });

			var tableListItem = $('#tableListItem').DataTable({
				// Data
                ajax: {
                    url: "<?=site_url('invoice/server_side_data_invoice');?>",
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
			});

			$('#form-barang-datang').validate({

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
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				},
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
					            data: $(form).serialize(),
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

					            	}

					            }

					        });

	                   	}
	                });

				}

			});

		});

	</script>


