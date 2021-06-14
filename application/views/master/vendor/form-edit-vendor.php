
	<div class="row">

		<div class="col-md-3">

			<div class="col-md-12">

				<li class="list-group-item list-group-item-action">
					<input type="text" onchange="load_vendor_list(this)" class="form-control" placeholder="Search">
				</li>

			</div>

			<div class="col-md-12 col-list table-responsive" style="margin-top: 15px">

				<div id="vendor-list"></div>

			</div>

		</div>
		<!-- /div.col -->

		<div class="col-md-6">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Edit Vendor</h3>
				</div>

				<form id="form-edit-vendor" role="form" method="post" action="<?=site_url('vendor/update_vendor/' . $vendor['id']);?>">

				<div class="card-body">

					<div class="form-group">

						<label for="vendor_name">Vendor</label>
						<input type="text" id="vendor_name" name="vendor_name"
							   class="form-control" value="<?php echo $vendor['name'];?>">

					</div>

					<div class="form-group">

						<label for="vendor_name">Address</label>
						<textarea id="vendor_address" name="vendor_address"
								  class="form-control"><?php echo $vendor['address'];?></textarea>

					</div>

					<div class="form-group">

						<label for="vendor_name">NPWP</label>
						<input type="text" id="vendor_npwp" name="vendor_npwp"
							   class="form-control" value="<?php echo $vendor['npwp'];?>">

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
							<div class="badge badge-pill badge-info pull-right">Terakhir diubah</div><br>
							<?php echo date_format(date_create($vendor['updated_time']), 'd M Y H:i');?>
						</li>
						<li class="item">
							<div class="badge badge-pill badge-info pull-right">Diubah oleh</div><br>
							<?php echo $vendor['username'];?>
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

			$('#vendor_name').focus();

			load_vendor_list();

			$('#form-edit-vendor').validate({

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
						required: 'Kolom gudang harus diisi'
					},
					vendor_address: {
						required: 'Kolom alamat gudang harus diisi'
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

											$('#form-edit-vendor input, #form-edit-vendor textarea').removeClass('is-valid');
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

											$('#form-edit-vendor input').addClass('is-invalid');

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

		function load_vendor_list(e) {

			var id = "<?php echo $vendor_id;?>";

			var search;

			if (e == null) {
				search = '';
			} else {
				search = e.value;
			}

			$.ajax({
                url: "<?=site_url('vendor/load_vendor_list');?>?vendor=" + search,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                	$('#vendor-list').html(loading);
                },
                success: function(response){

					$('#vendor-list').html('');

                	var row = '<ul class="list-group" id="list-tab">';

                    for (index in response.result) {
                		
                		var link = "<?=site_url('vendor/form_edit_vendor');?>?vendor_id=" + response.result[index].id;

                    	if (response.result[index].id == id) {
                    		addClass = 'active';
                    	} else {
                    		addClass = '';
                    	}

                    	row += '<a href="' + link + '" class="list-group-item list-group-item-action ' + addClass + '">' + response.result[index].name + '</a>';
	  
	                }

	                row += '</ul>';

	                $('#vendor-list').append(row);

                }

            });

		}
		// End of load_vendor_list

	</script>




