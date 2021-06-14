
	<div class="row">

		<div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2">

			<div class="card">
				
				<div class="card-header">
					<h3 class="card-title">Tambah Barang</h3>
				</div>

				<form id="form-new-item" role="form" method="post" action="<?=site_url('item/save_new_item');?>">

				<div class="card-body">

					<div class="form-group">
						<label for="item_category">Kategori</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<input type="text" id="item_category" name="item_category"
									   class="form-control form-left" placeholder="Buat master baru">
							</div>
							<select id="select_category" name="select_category"
									class="form-control select2">
								<option value="" selected disabled>Pilih Kategori</option>

								<?php foreach ($category as $row) :
									if ($row->category != '') :
										echo '<option value="' . $row->id . '">' . $row->category . '</option>';
									endif;
								endforeach; ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="item_name">Nama Barang</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<input type="text" id="item_name" name="item_name"
									   class="form-control form-left" placeholder="Buat master baru">
							</div>
							<select id="select_item" name="select_item"
									class="form-control select2">
								<option value="" selected disabled>Pilih Barang</option>

								<?php foreach ($itemname as $row) :
									if ($row->name != '') :
										echo '<option value="' . $row->id . '">' . $row->name . '</option>';
									endif;
								endforeach; ?>
							</select>
						</div>
					</div>

					<!-- <div class="form-group">

						<label for="item_code">Kode</label>
						<input type="text" id="item_code" name="item_code"
							   class="form-control">

					</div> -->

					<div class="form-group">
						<label for="item_color">Warna</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<input type="text" id="item_color" name="item_color"
									   class="form-control form-left" placeholder="Buat master baru">
							</div>
							<select id="select_color" name="select_color"
									class="form-control select2">
								<option value="" selected disabled>Pilih Warna</option>

								<?php foreach ($color as $row) :
									if ($row->color != '') :
										echo '<option value="' . $row->id . '">' . $row->color . '</option>';
									endif;
								endforeach; ?>
							</select>
						</div>
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

			$('input:first').focus();

			$('#select_item').on('change', function ()  {
				$('#item_name').val($('#select_item option:selected').text());
			});

			$('#select_color').on('change', function () {
				$('#item_color').val($('#select_color option:selected').text());
			});

			$('#select_category').on('change', function () {
				$('#item_category').val($('#select_category option:selected').text());
			});

			$('#form-new-item').validate({

				rules: {
					item_name: {
						required: true
					},
					item_code: {
						required: true
					},
					item_category: {
						required: true
					}
				},
				messages: {
					item_name: {
						required: 'Kolom nama barang harus diisi'
					},
					item_code: {
						required: 'Kolom kode harus diisi'
					},
					item_category: {
						required: 'Kolom kategori harus diisi'
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

											$('#form-new-item input').removeClass('is-valid');
											$('#form-new-item select').removeClass('is-valid');
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

											$('#form-new-item input').addClass('is-invalid');
											$('#form-new-item select').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} else if (response == 'error-duplicate-item') {

					            		setTimeout(function(){

											$('#item_name').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Nama barang sudah terdaftar!'
										});

					            	} else if (response == 'error-duplicate-code') {

					            		setTimeout(function(){

											$('#item_code').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Kode barang sudah terdaftar!'
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

