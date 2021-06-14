
	<div class="row">

		<div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Tambah Vendor</h3>
				</div>

				<form id="form-new-vendor" method="post" action="<?=site_url('vendor/save_new_vendor');?>">

				<div class="card-body">

					<div class="form-group">

						<label for="vendor_name">Vendor</label>
						<input type="text" id="vendor_name" name="vendor_name"
							   class="form-control">

					</div>

					<div class="form-group">

						<label for="vendor_address">Address</label>
						<textarea id="vendor_address" name="vendor_address"
								  class="form-control"></textarea>

					</div>

					<div class="form-group">

						<label for="vendor_npwp">NPWP</label>
						<input type="text" id="vendor_npwp" name="vendor_npwp"
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

			$('#vendor_name').focus();

			$('#form-new-vendor').validate({

				rules: {
					vendor_name: {
						required: true
					},
					vendor_address: {
						required: true
					}
				},
				messages: {
					vendor_name: {
						required: 'Kolom vendor harus diisi'
					},
					vendor_address: {
						required: 'Kolom alamat harus diisi'
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

											$('#form-new-vendor input, #form-new-vendor textarea').removeClass('is-valid');
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

											$('#form-new-vendor input').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} else if (response == 'error') {

					            		setTimeout(function(){

											$('#vendor_name').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Vendor sudah terdaftar!'
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




