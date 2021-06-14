
	<div class="row">

		<div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Tambah Metode Pembayaran</h3>
				</div>

				<form id="form-new-payment-method" role="form" method="post" action="<?=site_url('payment_method/save_new_payment_method');?>">

				<div class="card-body">

					<div class="form-group">

						<label for="name">Nama</label>
						<input type="text" id="name" name="name"
							   class="form-control">

					</div>

					<div class="form-group">

						<label for="type">Tipe</label>
						<select id="type" name="type" 
								class="form-control select2">
								<option value="" selected disabled>Pilih Tipe</option>
								<?php foreach ($type as $row) :
									echo '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
								endforeach;
								?>

						</select>

					</div>

					<div class="form-group">

						<label for="bank_number">Bank</label>

						<div class="input-group">

							<div class="input-group-prepend">
								<input type="text" id="bank_name" name="bank_name"
							   		   class="form-control form-left">
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
							   class="form-control">

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

	</div>
	<!-- /div.row -->

	<script>

		$(document).ready( function () {

			$('#payment_method').focus();

			$('#select_bank_name').on('change', function () {

				var value = $(this).val();

				$('#bank_name').val(value);

			});

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

											$(".select2").val('').trigger('change') ;

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

											$('#form-new-payment-method input').removeClass('is-valid');

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

