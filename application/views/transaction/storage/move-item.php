
    
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
        .dataTables_length {
        	padding: 5px;
        }
    </style>

	<div class="row">

		<?php 

		if ($this->session->userdata('p_move_item_add') == 1 AND date('H:i:s') < $closingDate) { ?>

		<div class="col-md-6">

			<div class="card">

				<div class="card-header border-0">
                    <h3 class="card-title">Pindah Barang</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">    

                	<div class="row">

                		<div class="col-md-4">

                			<div class="input-group">

                				<select id="warehouse_id" class="form-control select2">
                					<option value="" selected disabled>Pilih Gudang</option>
                					<?php foreach ($warehouse as $row) :
                						echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                					endforeach; ?>
                				</select>

                			</div>

                		</div>
                		<!-- /div.col -->

	                	<div class="col-md-3">

	                		<div class="input-group">
			                	<input type="text" id="code" onchange="insert_item(this)"
			                		   class="form-control" autofocus>
			                	<div class="input-group-append">
			                		<button id="btn-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
			                	</div>

		                	</div>

		                </div>
		                <!-- /div.col -->

		                <div class="col-md-1">

		                	<!-- <button id="btn-template" class="btn btn-primary ml-1"><i class="fa fa-archive"></i></button> -->

		                </div>
	                	<!-- /div.col -->

	                	<div class="col-md-4">

	                		

	                	</div>
	                	<!-- /div.col -->

	                </div>
	                <!-- /div.row -->

	                <br>

	                <div class="row">

	                	<div class="col-md-12">

	                		<h3 id="scanned" class="bg-lightblue p-2">###</h3>

	                	</div>

	                </div>

	                <br>

                	<form id="form-move-item" method="post">

	                <div class="row">

	                	<div class="col-md-12">

		                	<table id="table-item-list" class="table table-stripted table-valign-middle">

		                		<head>
		                			<tr class="text-center">
		                				<th style="width: 15%">Kode</th>
		                				<th style="width: 30%">Barang</th>
		                				<th style="width: 15%">Qty</th>
		                				<th style="width: 5%"></th>
		                			</tr>
		                		</head>

		                		<tbody>

		                		</tbody>

		                	</table>

		                </div>
		                <!-- /div.col -->

		            </div>
		            <!-- /div.row -->

                </div>
                <!-- /div.card-body -->

                <div class="card-footer">
                	<button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
                <!-- /div.card-footer-->
            	</form>

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

		<?php } ?>

		<?php
		if ($this->session->userdata('p_move_item_add') == 1 AND date('H:i:s') < $closingDate) {
			echo '<div class="col-md-6">'; 
		} else { 
			echo '<div class="col-md-12">';
		} ?>

			<div class="card card-secondary">

				<div class="card-header">

					<h3 class="card-title">Data Pindah Barang</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

				</div>

				<div class="card-body p-0">

					<table id="table-list-move-item" class="table table-stripted table-valign-middle m-0" style="width: 100%">

						<thead>

							<tr class="text-center">
								<th>Tanggal</th>
								<th>Gudang</th>
								<th>Tujuan</th>
								<th></th>
							</tr>

						</thead>

						<tbody>

						</tbody>

					</table>

				</div>

			</div>
			<!-- /div.card -->

			<div id="display-detail"></div>

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<script>

		$(document).ready( function () {

			$('#btn-search').on('click', function () {

                $('div#modal-placehorder').load("<?=site_url('storage/load_modal_item');?>");

			});

			$('#btn-template').on('click', function () {

                $('div#modal-placehorder').load("<?=site_url('storage/load_modal_template');?>");

			});

			var tableListMoveItem = $('#table-list-move-item').DataTable({

				 // Data
                ajax: {
                    url: "<?=site_url('storage/server_side_data_list_move_item');?>",
                    type: "POST"
                },
                processing: true,

                // Ordering
                order: [0, 'asc'],
                colReorder: true,
                orderCellsTop: true,

                // Header
                // fixedHeader: {
                //     headerOffset: 55
                // },

                info: false,

                // Select
                // select: {
                //     style: 'single'
                // },
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

                // Length
                lengthMenu: [
                    [ 10, 25, 50, 100],
                    [ '10 rows', '25 rows', '50 rows', '100 rows']
                ],

			});

		});

		function insert_item(param) {

			var code = param.value;

			$.ajax({
				url: "<?=site_url('storage/insert_item');?>",
				type: "post",
				data: {
					code: code
				},
				dataType: "json",
				success: function(data){

					if (data.response == 'error-null') {

						Swal.fire({
									icon: 'error',
									text: 'Barang tidak ditemukan!',
									showConfirmButton: false,
									timer: 1500
								});

						$('#code').val('');

					} else {
						insert_row(data);
						$('#scanned').html('#' + data.code + ' - ' + data.name);
						$('#code').val('');
					}

				}
			})

		}

		function insert_row(data) {

			if (check_row(data.id) != 0) {

				if ($('#item-qty-' + data.id).val() != '') {
					var qty = 0;
					qty = +$('#item-qty-' + data.id).val() + 1;
					$('#item-qty-' + data.id).val(qty);
				}
			} else {

				var tableItemList = document.getElementById('table-item-list');

				var row = tableItemList.insertRow(1);

				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);

				cell1.innerHTML = data.code + '<input type="hidden" class="item-id" name="item_id[]" value="' + data.id + '" id="i-' + data.id + '" data-id="' + data.id + '">';
				cell2.innerHTML = data.name;
				cell3.innerHTML = '<input type="number" name="item_qty[]" class="form-control" min="1" step="1" value="' + data.qty + '" id="item-qty-' + data.id + '">';
				cell3.innerHTML += '<input type="hidden" name="template_id[]" value="' + data.template_id + '">';
				cell4.innerHTML = '<a onclick="delete_row(this, ' + data.id + ')"><i class="fa fa-times-circle text-red"></i></a>';
			}
		}

		function check_row(id) {

			var check = 0;

			$('.item-id').each( function () {

				var x = $(this).data('id');

				if (x == id) {
					check = id;
				}
			});

			return check;
		}

		function delete_row(param, id) {

			var i = param.parentNode.parentNode.rowIndex;
			document.getElementById("table-item-list").deleteRow(i);

		}

		$("#form-move-item").submit( function (e) {
			e.preventDefault();

			var warehouse_id = $("#warehouse_id").val();

			if (warehouse_id == null) {

				Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Pilih Gudang!',
                        });

				return false;

			}

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
						url: "<?=site_url('storage/save_move_item');?>",
						data: $(this).serialize()+'&warehouse_id=' + warehouse_id,
						type: $(this).attr("method"),
						success: function(response) {

							 if (response == 'success') {

		                        Swal.fire({
		                            position: 'center',
		                            icon: 'success',
		                            title: 'Data berhasil disimpan',
		                            showConfirmButton: false,
		                            timer: 1500
		                        });

		                        setTimeout(function(){

		                        	location.href = "<?=site_url('storage/move_item');?>";	

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

         });

		function display_detail(id) {

			$.ajax({
                url: "<?=site_url('storage/load_detail_move_item');?>",
                type: "get",
                data: {
                	id: id
                },
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#display-detail').html(loading);
                },
                success: function (response) {

                    $('#display-detail').html(response);

                }

            });

		}

	</script>