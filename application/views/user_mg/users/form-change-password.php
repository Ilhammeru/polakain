
	<div class="row">

		<div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Ubah Password</h3>
				</div>

				<form id="form-change-password" role="form" method="post" action="<?=site_url('users/save_new_password');?>">

				<div class="card-body">

					<div class="form-group">

						<label for="user_password">Password</label>
						<input type="password" id="user_password" name="user_password"
							   class="form-control">

					</div>

					<div class="form-group">

						<label for="user_passwordv">Verifikasi Password</label>
						<input type="password" id="user_passwordv" name="user_passwordv"
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

			$('#form-change-password').validate({

				rules: {
					user_password: {
						required: true,
						minlength: 8
					},
					user_passwordv: {
						required: true,
						minlength: 8,
						equalTo: '#user_password'
					}
				},
				messages: {
					user_password: {
						required: 'Kolom password harus diisi',
						minlength: 'Kolom password minimal 8 karakter'
					},
					user_passwordv: {
						required: 'Kolom verifikasi password harus diisi',
						minlength: 'Kolom verifikasi password minimal 8 karakter',
						equalTo: 'Kolom verifikasi password tidak sama'
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

					$.ajax({
			            url: form.action,
			            type: form.method,
			            data: $(form).serialize(),
			            success: function(response) {

			            	if (response == 'success') {
	
			            		setTimeout( function (){

									$('input').removeClass('is-valid');
									$(form)[0].reset();	

								}, 1000);

			            		Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Data berhasil disimpan',
									showConfirmButton: false,
									timer: 1500
								});

			            	} else if (response == 'error') {

			            		setTimeout(function(){

									$('#form-change-password input').addClass('is-invalid');

								}, 1000);

			            		Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Ada kesalahan!'
								});

			            	}

			            }
			            // End of success

			        });
			        // End of ajax submit

				}
				// End of submitHandler

			});
			// End of form validate

		});
		// End of function()

	</script>




