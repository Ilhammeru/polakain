
	<div class="row">

		<div class="col-md-3 col-list table-responsive">

			<div id="payment-method-list"></div>
					
		</div>
		<!-- /div.col -->

		<div class="col-md-6">

			<div class="card card-primary">

				<div class="ribbon-wrapper ribbon-lg">
					<?php 
					if ($payment_method['status'] == 'Aktif') {
					?>
					<div class="ribbon bg-info">
						AKTIF
					</div>
					<?php
					} elseif ($payment_method['status'] == 'Tidak Aktif') {
					?>
					<div class="ribbon bg-secondary">
						TIDAK AKTIF
					</div>
					<?php } ?>
				</div>
				
				<div class="card-header">
					<h3 class="card-title">Edit Metode Pembayaran</h3>
				</div>

				<form id="form-new-payment-method" role="form" method="post" action="<?=site_url('payment_method/update_payment_method/' . $payment_method['id']);?>">

				<div class="card-body">

					<div class="form-group">

						<label for="name">Nama</label>
						<input type="text" id="name" name="name"
							   class="form-control" value="<?php echo $payment_method['name'];?>">

					</div>

					<div class="form-group">

						<label for="type">Tipe</label>
						<select id="type" name="type" 
								class="form-control select2">
								<?php foreach ($type as $row) :
									if ($row['type'] == $payment_method['type']) {
										echo '<option value="' . $row['id'] . '" selected>' . $row['type'] . '</option>';
									} else {
										echo '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
									}
								endforeach;
								?>

						</select>

					</div>

					<div class="form-group">

						<label for="bank_number">Bank</label>

						<div class="input-group">

							<div class="input-group-prepend">
								<input type="text" id="bank_name" name="bank_name"
							   		   class="form-control form-left" value="<?php echo $payment_method['bank_name'];?>">
							</div>

							<select id="select_bank_name" name="select_bank_name"
									class="form-control select2">
									<option value="" selected disabled>Pilih Bank</option>
									<?php foreach ($bank_name as $row) :

										if ($row->bank_name != '') :
											echo '<option>' . $row->bank_name . '</option>';
										endif;

									endforeach; ?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="bank_number">Nomor Rekening</label>
						<input type="text" id="bank_number" name="bank_number"
							   class="form-control" value="<?php echo $payment_method['bank_number'];?>">

					</div>

					<div class="form-group">

						<label for="status">Status</label>
						<select id="status" name="status" 
								class="form-control select2">
							<?php foreach ($status as $row) :

								if ($payment_method['status'] == $row['status']) {
									echo '<option value="' . $row['id'] . '" selected>' . $row['status'] . '</option>';
								} else {
									echo '<option value="' . $row['id'] . '">' . $row['status'] . '</option>';
								}

							endforeach;
							?>
						</select>

					</div>

				</div>
				<!-- /div.card-body -->

				<div class="card-footer">
					<button type="submit" class="btn btn-primary float-right">Submit</button>
				</div>
				<!-- /div.card-footer -->

				</form>

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

		<div class="col-md-3">

			<div class="card card-info">

				<div class="card-header">
					<h3 class="card-title">Informasi</h3>
				</div>

				<div class="card-body p-3">

					<ul class="products-list product-list-in-card">

						<li class="item">
							<div class="badge badge-pill badge-info float-right">Terakhir diubah</div><br>
							<?php echo date_format(date_create($payment_method['updated_time']), 'd M Y H:i');?>
						</li>
						<li class="item">
							<div class="badge badge-pill badge-info float-right">Diubah oleh</div><br>
							<?php echo $payment_method['username'];?>
						</li>

					</ul>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<script>

		$(document).ready( function () {

			$('#payment_method').focus();

			$('#select_bank_name').on('change', function () {

				var value = $(this).val();

				$('#bank_name').val(value);

			});

			load_payment_method_list();

			function load_payment_method_list() {

				var id = "<?php echo $payment_method_id;?>";

				$.ajax({
                    url: "<?=site_url('payment_method/load_payment_method_list');?>",
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function() {
                    	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    	$('#payment-method-list').html(loading);
                    },
                    success: function(response){

						$('#payment-method-list').html('');

                    	var row = '<ul class="list-group" id="list-tab">';

                        for (index in response.result) {
                    		
                    		var link = "<?=site_url('payment_method/form_edit_payment_method');?>?payment_method_id=" + response.result[index].id;

                        	if (response.result[index].id == id) {
                        		addClass = 'active';
                        	} else {
                        		addClass = '';
                        	}

                        	row += '<a href="' + link + '" class="list-group-item list-group-item-action ' + addClass + '">' + response.result[index].name + '</a>';
		  
		                }

		                row += '</ul>';

		                $('#payment-method-list').append(row);

                    }

                });

			}
			// End of load_payment_method_list

			$('#form-new-payment-method').validate({

				rules: {
					name: {
						required: true
					},
					type: {
						required: true
					},
					bank_name: {
						required: true
					},
					bank_number: {
						required: true
					}
				},
				messages: {
					name: {
						required: 'Kolom nama harus diisi'
					},
					type: {
						required: 'Pilih Tipe'
					},
					bank_name: {
						required: 'Kolom bank harus diisi'
					},
					bank_number: {
						required: 'Kolom nomor rekening harus diisi'
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

											$('#form-new-payment-method input').removeClass('is-valid');
											$(form)[0].reset();	

										}, 1000);

					            		Swal.fire({
											position: 'center',
											icon: 'success',
											title: 'Data berhasil disimpan',
											showConfirmButton: false,
											timer: 1500
										});

					            	} else if (response == 'error-null') {

					            		setTimeout(function(){

											$('#form-new-payment-method input').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} else if (response == 'error-duplicate') {

					            		setTimeout(function(){

											$('#payment_method').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Metode pembayaran sudah terdaftar!'
										});

					            	}

					            }
					            // End of success

					        });
					        // End of ajax submit

	                   	}
	                });

				}
				// End of submitHandler

			});
			// End of form validate

		});
		// End of function()

	</script>

