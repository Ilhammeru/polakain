	
	<script>

		$(document).ready( function () {

			$('#modal-add-brand').modal('show');

			$("body").on("shown.bs.modal", "#modal-add-brand", function () {

				$('#brand_name').focus();

				$('.select2').select2();

				$('#select_brand').on('change', function () {
					$('#brand_name').val(this.value);
				});

			});

			$('#form-add-brand').validate({

				rules: {
					brand_name: {
						required: true
					},
					brand_category: {
						required: true
					}
				},
				messages: {
					brand_name: {
						required: 'Kolom brand harus diisi'
					},
					brand_category: {
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

					            	if (response.status == 'success') {

					            		setTimeout(function(){

											$('#form-add-brand input, #form-add-brand textarea').removeClass('is-valid');
											$(form)[0].reset();	

											$("#modal-add-brand").modal('hide');

										}, 1000);

					            		Swal.fire({
											position: 'center',
											icon: 'success',
											title: 'Data berhasil disimpan',
											showConfirmButton: false,
											timer: 1500
										});

										setTimeout(function(){

											location.href = "<?=site_url('template_item/submit_data?brand_id=');?>" + response.brand_id;

										}, 2000);

					            	} else if (response.status == 'error-null') {

					            		setTimeout(function(){

											$('#form-add-brand input').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} else if (response.status == 'error-duplicate') {

					            		setTimeout(function(){

											$('#brand_name').addClass('is-invalid');
											$('#brand_category').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Brand & kategori sudah terdaftar!'
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

	</script>

	<div id="modal-add-brand" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title"><i class="fa fa-plus"></i> Tambah Data</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<form id="form-add-brand" role="form" method="post" action="<?=site_url('template_item/save_add_brand');?>">

				<div class="modal-body">

					<div class="form-group">

						<label for="brand_name">Nama Brand</label>
						<div class="input-group">

							<div class="input-group-prepend">
								<input type="text" id="brand_name" name="brand_name"
							   class="form-control form-left">
							</div>

							<select id="select_brand" name="select_brand"
									class="form-control select2">
								<option value="" selected disabled>Pilih Brand</option>
								<?php foreach ($brand as $row) :

									echo '<option>' . $row->brand . '</option>';

								endforeach; ?>

							</select>

						</div>

					</div>

					<div class="form-group">
						<label for="brand_category">Kategori</label>
						<input type="text" id="brand_category" name="brand_category"
							   class="form-control">
					</div>
					
					<div class="errorTxt"></div>

				</div>

				<div class="modal-footer">

					<div class="btn-group">
						<button type="submit" id="btn-delete" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					</div>

				</div>

				</form>

			</div>
			<!-- /div.modal-content -->

		</div>
		<!-- /div.modal-dialog -->

	</div>
	<!-- /div.modal -->