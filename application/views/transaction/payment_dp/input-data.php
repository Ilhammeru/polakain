
	<style>
		.dt-buttons {
			padding: 5px;
		}
		.dataTables_info {
			padding: 5px;
		}
		ul.pagination {
			padding: 5px;
		}
		.btn-delete-cancel:hover, .btn-cancel:hover {
			background-color: #007bff;
			color: white;
		}
	</style>

	<div class="row">

		<?php

		if ($this->session->userdata('p_payment_dp_add') == 1 AND date('Y-m-d') < $closingDate) { ?>

		<div class="col-md-4">

			<div class="card card-primary">
				
				<div class="card-header">
					<h3 class="card-title">Input Pembelian Dimuka</h3>
				</div>

				<form id="form-payment-dp" method="post" action="<?=site_url('payment_dp/save_payment_dp');?>">
				<div class="card-body">

					<div class="form-group">

						<label for="vendor_id">Vendor</label>
						<select id="vendor_id" name="vendor_id"
								class="form-control select2">
								<option value="" selected disabled>Pilih Vendor</option>
								<?php foreach ($vendor as $row) :
									echo '<option value="' . $row->id . '">' . $row->name . '</option>';
								endforeach; ?>
						</select>

					</div>

					<div class="form-group">

						<label for="nominal">Nominal</label>
						<input type="text" id="nominal" name="nominal"
							   class="form-control currency-rp">

					</div>

					<div class="form-group">

						<label for="payment_method_id">Metode Pembayaran</label>
						<select id="payment_method_id" name="payment_method_id"
								class="form-control select2">
								<option value="" selected disabled>Pilih Metode Pembayaran</option>
								<?php foreach ($payment_method as $row) :
									echo '<option value="' . $row->id . '">' . $row->method . '</option>';
								endforeach; ?>
								<option value="z">Tunai</option>
						</select>

					</div>

					<div class="form-group">

						<label for="note">Keterangan</label>
						<textarea id="note" name="note"
								  class="form-control"></textarea>

					</div>

				</div>

				<div class="card-footer">
					<button class="btn btn-primary float-right">Submit</button>
				</div>
				</form>

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

		<?php } ?>
	
		<?php
		if ($this->session->userdata('p_payment_dp_add') == 1 AND date('Y-m-d') < $closingDate) {
			echo '<div class="col-md-8">';
		} else {
			echo '<div class="col-md-12">';
		}
		?>

			<div class="card card-secondary">
				
				<div class="card-header">
					<h3 class="card-title">Pembelian Dimuka</h3>
				</div>

				<div class="card-body p-0">

					<table id="table-payment-dp" class="table table-striped table-valign-middle m-0">

						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Vendor</th>
								<th>Nominal</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
						</tbody>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->
			
			<div class="card card-info">
				
				<div class="card-header">
					<h3 class="card-title">Pembelian Dimuka Clear</h3>
				</div>

				<div class="card-body p-0">

					<table id="table-payment-dp-used" class="table table-striped table-valign-middle m-0">

						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Nomor Invoice</th>
								<th>Vendor</th>
								<th>Keterangan</th>
								<th>Nominal</th>
							</tr>
						</thead>

						<tbody>
						</tbody>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

			<div class="card card-secondary">
				
				<div class="card-header">
					<h3 class="card-title">Pembelian Dimuka Cancel</h3>
				</div>

				<div class="card-body p-0">

					<table id="table-payment-dp-cancel" class="table table-striped table-valign-middle m-0">

						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Nomor Invoice</th>
								<th>Vendor</th>
								<th>Tanggal Cancel</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
						</tbody>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->


    <!-- Modal -->
    
	<div id="modal-cancel-dp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title"><i class="fa fa-info-circle"></i> Cancel DP</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<form id="form-cancel-dp" method="post">
				<input type="hidden" name="id" id="id">
				<div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tanggal cancel</label>
						<input type="date" class="form-control" name="date_cancel" required>
                    </div>        

                    <div class="mb-3">
                        <label class="form-label">Rekening</label>
						<select id="payment_method" name="payment_method" class="form-control" required>
							<option value="" selected disabled>Pilih Metode Pembayaran</option>
							<?php foreach ($payment_method as $list) :
								echo '<option value="' . $list->id . '">' . $list->method . '</option>';
							endforeach;

							echo '<option value="z">Tunai</option>';
							?>
						</select>
                    </div>        

                </div>

				<div class="modal-footer">

					<div class="btn-group">
                    	<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    	<button type="submit" class="btn btn-primary btn-sm">Submit</button>
					</div>

				</div>
				</form>

			</div>
			<!-- /div.modal-content -->

		</div>
		<!-- /div.modal-dialog -->

	</div>
	<!-- /div.modal -->

	<script>

		$(document).ready( function () {

			var titleExport = 'Pembelian Dimuka';

			$('#table-payment-dp').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('payment_dp/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [0, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollCollapse: true,
                // Column
                columnDefs: [
								{ 
									targets: [ 0 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 3 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],   
                // Buttons          
                // buttons: [ 
                //             {
                //                 extend: 'copy'
                //             },
                //             {
                //                 extend: 'excel',
                //                 title: titleExport
                //             },
                //             {
                //                 extend: 'pdf',
                //                 title: titleExport
                //             },
                //             {
                //                 extend: 'print',
                //                 title: titleExport
                //             }
                //         ],
                // dom: 'Bfrtip'

			});

			$('#table-payment-dp-used').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('payment_dp/server_side_data_used');?>",
                    type: "POST"
                },
                processing: true,
                searching: false,
                colReorder: true,
                // Ordering
                order: [0, 'desc'],
                colReorder: true,
                orderCellsTop: true,

                // // Header
                // fixedHeader: {
                //     headerOffset: 55
                // },

                // // Select
                // select: {
                //     style: 'multi'
                // },

                // Length
                lengthChange: false,
                lengthMenu: [
                    [ 10, 25, 50, 100],
                    [ '10 rows', '25 rows', '50 rows', '100 rows']
                ],
                columnDefs: [
								{ 
									targets: [ 0 ],
									type: "de_datetime"
								}
				]
                // Buttons          
                // buttons: [ 
                //             {
                //                 extend: 'copy'
                //             },
                //             {
                //                 extend: 'excel',
                //                 title: titleExport
                //             },
                //             {
                //                 extend: 'pdf',
                //                 title: titleExport
                //             },
                //             {
                //                 extend: 'print',
                //                 title: titleExport
                //             }
                //         ],
                // dom: 'Bfrtip'

			});

			$('#table-payment-dp-cancel').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('payment_dp/server_side_data_cancel');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [0, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollCollapse: true,
                columnDefs: [
								{ 
									targets: [ 0 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 4 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],   
			});

			$('#form-payment-dp').validate({

				rules: {	
					vendor_id: {
						required: true
					},
					nominal: {
						required: true
					},
					payment_method_id: {
						required: true
					}
				},
				messages: {
					vendor_id: {
						required: 'Pilih vendor'
					},
					nominal: {
						required: 'Kolom nominal harus diisi'
					},
					payment_method_id: {
						required: 'Pilih metode pembayaran'
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

											$('#form-payment-dp input').removeClass('is-valid');
											$('#form-payment-dp textarea').removeClass('is-valid');
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

										setTimeout(function () {
											$('#table-payment-dp').DataTable().ajax.reload();
										}, 2000);

					            	} else if (response == 'error') {

					            		setTimeout(function(){

											$('#form-payment-dp input').removeClass('is-valid');
											$('#form-payment-dp textarea').removeClass('is-valid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
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

		function delete_dp(key) {

			 Swal.fire({
                title: 'Anda yakin ingin menghapus data?',
                //text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus!'
                }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?=site_url('payment_dp/delete_dp');?>",
                        type: "post",
                        data: {
                            id: key
                        },
                        success: function (response) {

                            if (response == 'success') {

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Data berhasil dihapus',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                setTimeout(function(){

                                    $('#table-payment-dp').DataTable().ajax.reload();

                                }, 1500);

                            } else if (response == 'error') {
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Ada kesalahan!',
                                });

                            }

                        }

                    });

                }

            });

		}

		function cancel_dp(id) {
			
			$('#id').val(id);
			$('#modal-cancel-dp').modal('show');
		}

		function delete_cancel(id) {

			Swal.fire({
            title: 'Yakin hapus data?',
            //text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Submit!'
            }).then((result) => {

                if (result.isConfirmed) {
			
					$.ajax({
						url: "<?=site_url('payment_dp/delete_cancel_dp');?>",
						type: "post",
						data: {
							id: id
						},
						success: function () {

							Swal.fire({
								position: 'center',
								icon: 'success',
								title: 'Data berhasil dihapus',
								showConfirmButton: false,
								timer: 1500
							});

							setTimeout( function () {
								$('#table-payment-dp').DataTable().ajax.reload();
								$('#table-payment-dp-cancel').DataTable().ajax.reload();
							}, 1500);

						}
					});
				
				}
			});
		}

		 $('#form-cancel-dp').submit( function (e) {
            e.preventDefault();
            
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
						url: "<?=site_url('payment_dp/cancel_dp');?>",
						data: $(this).serialize(),
						type: $(this).attr("method"),
						success: function(response) {

							Swal.fire({
								position: 'center',
								icon: 'success',
								title: 'Data berhasil disimpan',
								showConfirmButton: false,
								timer: 1500
							});

							setTimeout( function () {
								$('#modal-cancel-dp').modal('hide');
                                $('#table-payment-dp').DataTable().ajax.reload();
							}, 1500);

						}
					});
                
                }

            });

        });

	</script>