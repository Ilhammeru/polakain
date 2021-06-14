
	<div class="row">

		<div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Tambah User</h3>
				</div>

				<form id="form-new-user" role="form" method="post" action="<?=site_url('users/save_new_user');?>">

				<div class="card-body">

					<div class="form-group">

						<label for="username">Username</label>
						<input type="text" id="username" name="username"
							   class="form-control" style="text-transform: none">

					</div>

					<div class="form-group">

						<label for="dept">Departement</label>
						<select id="dept" name="dept"
								class="form-control select2">
								<option value="" selected disabled>Pilih Department</option>
								
								<?php foreach ($dept as $row) :

									echo '<option value="' . $row->id . '">' . $row->name . '</option>';

								endforeach; ?>

						</select>

					</div>

					<div class="form-group">

						<label for="role">Hak Akses</label>
						<select id="role" name="role"
								class="form-control select2">
								<option value="" selected disabled>Pilih Hak Akses</option>
								
								<?php foreach ($role as $row) :

									echo '<option value="' . $row->id . '">' . $row->name . '</option>';

								endforeach; ?>

						</select>

					</div>

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

			$('#username').focus();

			$('#form-new-user').validate({

				rules: {
					username: {
						required: true,
						minlength: 5,
					},
					dept: {
						required: true
					},
					role: {
						required: true,
					},
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
					username: {
						required: 'Kolom username harus diisi',
						minlength: 'Kolom username minimal 5 karakter'
					},
					dept: {
						required: 'Kolom departement harus diisi'
					},
					role: {
						required: 'Pilih Hak Akses'
					},
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

			            		setTimeout(function(){

									$('#form-new-user input, #form-new-user select').removeClass('is-valid');
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

									$('#form-new-user input, #form-new-user select').addClass('is-invalid');

								}, 1000);

			            		Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Ada kesalahan!',
								});

			            	} else if (response == 'error') {

			            		setTimeout(function(){

									$('#username').addClass('is-invalid');

								}, 1000);

			            		Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Username sudah terdaftar!'
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




