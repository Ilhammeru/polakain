
	<div class="row">

		<div class="col-md-6">

			<div class="card card-secondary">

				<div class="card-header">

					<h3 class="card-title">Data Penjualan</h3>

				</div>

				<div class="card-body">

					<table id="table-sales" class="table table-bordered table-striped table-valign-middle">

						<thead>
							<tr>
                                <th>Aksi</th>
								<th>Nomor Invoice</th>
								<th>Tanggal</th>
								<th>Nominal</th>
								<th>Status</th>
							</tr>
						</thead>

						<tbody>
						</tbody>

					</table>

				</div>
				<!-- /div.crad-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

        <div class="col-md-6">

            <div id="display-detail"></div>

        </div>
        <!-- /div.col -->

	</div>
	<!-- /div.row -->

	<script>

		var titleExport = 'Data Penjualan';
        var columnExport = [ ':visible:not(.not-export-col)' ];

		$(document).ready( function () {

			$('#table-sales thead tr').clone(true).appendTo('#table-sales thead');

            $('#table-sales thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 2 :

                        $(this).html('<input type="text" class="form-control " style="position: static" placeholder="Cari ' + title + '" readonly />');

                        break;

                    case 4 :

                    	$(this).html('<?=$filterStatus;?>');

                    	break;

                    case 0 :

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('.daterangepicker', this).on('apply.', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    
                    tableSales
                        .column(i)
                        .search(this.value)
                        .draw();
                });

                $('.daterangepicker', this).on('cancel.', function(ev, picker) {
                
                    $(this).val("");

                    tableSales
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('input', this).on('change', function () {

                    tableSales
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('select', this).on('keyup change', function () {

                    var result = [];
                    var options = this && this.options;
                    var opt;

                    for (var x = 0, xLen = options.length; x < xLen; x++) {
                            
                        opt = options[x];

                        if (opt.selected) {

                            result.push(opt.value || opt.text);
                            
                        }

                    }

                    tableSales
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

			var tableSales = $('#table-sales').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('sales/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,

                // Ordering
                order: [1, 'desc'],
                colReorder: true,
                orderCellsTop: true,
                
                // Fix Column
                fixedColumns: {
                    leftColumns: 1
                },
                scrollX: '100px',
                scrollCollapse: true,

                // Column
                columnDefs: [
                                {  
                                    targets: [ 0 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],   

                // // Select
                // select: false,

                // Length
                lengthChange: false,
                lengthMenu: [
                    [ 10, 25, 50, 100],
                    [ '10 rows', '25 rows', '50 rows', '100 rows']
                ], 

                // Buttons          
                // buttons: [ 
                //             'pageLength',
                //             'colvis', 
                //             {
                //                 extend: 'copy'
                //             },
                //             {
                //                 extend: 'excel',
                //                 title: titleExport,
                //                 exportOptions: {
                //                     columns: columnExport
                //                 }
                //             },
                //             {
                //                 extend: 'pdf',
                //                 title: titleExport,
                //                 exportOptions: {
                //                     columns: columnExport
                //                 }
                //             },
                //             {
                //                 extend: 'print',
                //                 title: titleExport,
                //                 exportOptions: {
                //                     columns: columnExport
                //                 }
                //             }
                //         ],
                // dom: 'Bfrtip',

			});

		});

        function display_detail(key) {

            $.ajax({
                url: "<?=site_url('sales/load_display_detail');?>",
                type: "get",
                data: {
                    id: key
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

        function delete_sales(key) {

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
                        url: "<?=site_url('sales/delete_sales');?>",
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

                                    $('#table-sales').DataTable().ajax.reload();

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

	</script>


