
	<div class="row">

		<div class="col-md-3 col-list table-responsive">

			<div id="warehouse-list"></div>
					
		</div>
		<!-- /div.col -->

		<div class="col-md-6">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Edit Gudang</h3>
				</div>

				<form id="form-edit-warehouse" role="form" method="post" action="<?=site_url('warehouse/update_warehouse/' . $warehouse['id']);?>">
				<div class="card-body">

					<div class="form-group">

						<label for="warehouse_name">Gudang</label>
						<input type="text" id="warehouse_name" name="warehouse_name"
							   class="form-control" value="<?php echo $warehouse['name'];?>">

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
							<?php echo date_format(date_create($warehouse['updated_time']), 'd M Y H:i');?>
						</li>
						<li class="item">
							<div class="badge badge-pill badge-info float-right">Diubah oleh</div><br>
							<?php echo $warehouse['username'];?>
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

			$('#warehouse_name').focus();

			load_warehouse_list();

			function load_warehouse_list() {

				var id = "<?php echo $warehouse_id;?>";

				$.ajax({
                    url: "<?=site_url('warehouse/load_warehouse_list');?>",
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function() {
                    	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    	$('#warehouse-list').html(loading);
                    },
                    success: function(response){

						$('#warehouse-list').html('');

                    	var row = '<ul class="list-group" id="list-tab">';

                        for (index in response.result) {
                    		
                    		var link = "<?=site_url('warehouse/form_edit_warehouse');?>?warehouse_id=" + response.result[index].id;

                        	if (response.result[index].id == id) {
                        		addClass = 'active';
                        	} else {
                        		addClass = '';
                        	}

                        	row += '<a href="' + link + '" class="list-group-item list-group-item-action ' + addClass + '">' + response.result[index].name + '</a>';
		  
		                }

		                row += '</ul>';

		                $('#warehouse-list').append(row);

                    }

                });

			}
			// End of load_warehouse_list

			$('#form-edit-warehouse').validate({

				rules: {
					warehouse_name: {
						required: true
					}
				},
				messages: {
					warehouse_name: {
						required: 'Kolom gudang harus diisi'
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

											$('#form-edit-warehouse input').removeClass('is-valid');
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

											$('#form-edit-warehouse input').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} else if (response == 'error') {

					            		setTimeout(function(){

											$('#warehouse_name').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Gudang sudah terdaftar!'
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




