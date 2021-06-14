
	<div class="row">
		<div class="col-md-3">

			<div class="col-md-12">

				<li class="list-group-item list-group-item-action">
					<input type="text" onchange="load_item_list(this)" class="form-control" placeholder="Search">
				</li>

			</div>

			<div class="col-md-12 col-list table-responsive" style="margin-top: 15px">

				<div id="item-list"></div>

			</div>

		</div>
		<!-- /div.col -->

		<div class="col-md-6">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Edit Barang</h3>
				</div>

				<form id="form-edit-item" role="form" method="post" action="<?=site_url('item/update_item/' . $item['id']);?>">

				<div class="card-body">

					<div class="form-group">
						<label for="item_category">Kategori</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<input type="text" id="item_category" name="item_category"
									   class="form-control form-left" placeholder="Buat master baru" value="<?=str_replace('"', '', $item['itemcategory']);?>">
							</div>
							<select id="select_category" name="select_category"
									class="form-control select2">
								<option value="" selected disabled>Pilih Kategori</option>

								<?php 
								echo '<option value="' . str_replace('"', '', $item['category_id']) . '" selected>' . str_replace('"', '', $item['itemcategory']) . '</option>';
								foreach ($category as $row) :
									if ($row->id != str_replace('"', '', $item['category_id'])) :
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
									   class="form-control form-left" placeholder="Buat master baru" value="<?=str_replace('"', '', $item['itemname']);?>">
							</div>
							<select id="select_item" name="select_item"
									class="form-control select2">
								<option value="" selected disabled>Pilih Barang</option>

								<?php 
								echo '<option value="' . str_replace('"', '', $item['name_id']) . '" selected>' . str_replace('"', '', $item['itemname']) . '</option>';
								foreach ($itemname as $row) :
									if ($row->id != str_replace('"', '', $item['name_id'])) :
										echo '<option value="' . $row->id . '">' . $row->name . '</option>';
									endif;
								endforeach; ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="item_color">Warna</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<input type="text" id="item_color" name="item_color"
									   class="form-control form-left" placeholder="Buat master baru" value="<?=str_replace('"', '', $item['itemcolor']);?>">
							</div>
							<select id="select_color" name="select_color"
									class="form-control select2">
								<option value="" selected disabled>Pilih Warna</option>

								<?php 
								echo '<option value="' . str_replace('"', '', $item['color_id']) . '" selected>' . str_replace('"', '', $item['itemcolor']) . '</option>';
								foreach ($color as $row) :
									if ($row->id != str_replace('"', '', $item['color_id'])) :
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

		<div class="col-md-3">

			<div class="card card-info">

				<div class="card-header">
					<h3 class="card-title">Informasi</h3>
				</div>

				<div class="card-body p-3">

					<ul class="products-list product-list-in-card">

						<li class="item">
							<div class="badge badge-pill badge-info float-right">Terakhir diubah</div><br>
							<?php echo date_format(date_create($item['updated_time']), 'd M Y H:i');?>
						</li>
						<li class="item">
							<div class="badge badge-pill badge-info float-right">Diubah oleh</div><br>
							<?php echo $item['username'];?>
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

			$('#item_category').focus();

			load_item_list();
			
			$('#select_item').on('change', function ()  {
				$('#item_name').val($('#select_item option:selected').text());
			});

			$('#select_color').on('change', function () {
				$('#item_color').val($('#select_color option:selected').text());
			});

			$('#select_category').on('change', function () {
				$('#item_category').val($('#select_category option:selected').text());
			});

			$('#form-edit-item').validate({

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

											$('#form-edit-item input').removeClass('is-valid');
											$('#form-edit-item select').removeClass('is-valid');
											// $(form)[0].reset();	

											// $(".select2").val('').trigger('change') ;

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

											$('#form-edit-item input').addClass('is-invalid');
											$('#form-edit-item select').removeClass('is-valid');

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

		function load_item_list(e) {

			var id = "<?php echo $item_id;?>";

			var search;

			if (e == null) {
				search = '';
			} else {
				search = e.value;
			}

			$.ajax({
                url: "<?=site_url('item/load_item_list');?>?item=" + search,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                	$('#item-list').html(loading);
                },
                success: function(response){

					$('#item-list').html('');

                	var row = '<ul class="list-group" id="list-tab">';

                    for (index in response.result) {
                		
                		var link = "<?=site_url('item/form_edit_item');?>?item_id=" + response.result[index].id;

                    	if (response.result[index].id == id) {
                    		addClass = 'active';
                    	} else {
                    		addClass = '';
                    	}

                    	row += '<a href="' + link + '" class="list-group-item list-group-item-action ' + addClass + '">' + response.result[index].name + '</a>';
	  
	                }

	                row += '</ul>';

	                $('#item-list').append(row);

                }

            });

		}
		// End of load_item_list

	</script>

