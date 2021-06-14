
    <?php

    if (date('H:i:s') < $closingDate) { ?>

	<div class="row">

		<div class="col-md-12">

			<div class="card card-primary">

				<div class="card-header">

					<h3 class="card-title">Approval</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

				</div>
				<!-- /div.card-header -->

				<div class="card-body p-0">

					<table id="table-approval" class="table table-striped table-valign-middle m-0" style="width: 100%">

						<thead>
							<tr>
								<th>Nomor Invoice</th>
								<th>Tanggal</th>
								<th>Nominal Bayar</th>
								<th>Metode Pembayaran</th>
								<th>Status</th>
								<th>Pilih Metode Pembayaran</th>
								<th>Jatuh Tempo</th>
								<th>Approve</th>
							</tr>
						</thead>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<div class="row">

		<div class="col-md-12">

			<div class="card card-warning">

				<div class="card-header">

					<h3 class="card-title">Data DP</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

				</div>
				<!-- /div.card-header -->

				<div class="card-body p-0">

					<table id="table-dp" class="table table-striped table-valign-middle m-0" style="width: 100%">

						<thead>
							<tr>
								<th>Nomor Invoice</th>
								<th>Customer</th>
								<th>Tanggal</th>
								<th>Total</th>
								<th>Nominal DP</th>
								<th>Pelunasan</th>
								<th>Metode Pembayaran</th>
								<th>Status</th>
								<th>Pilih Metode Pembayaran</th>
								<th>Approve</th>
							</tr>
						</thead>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<div class="row">

		<div class="col-md-12">

			<div class="card card-danger">

				<div class="card-header">

					<h3 class="card-title">Data Piutang</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

				</div>
				<!-- /div.card-header -->

				<div class="card-body p-0">

					<table id="table-piutang" class="table table-striped table-valign-middle m-0" style="width: 100%">

						<thead>
							<tr>
								<th>Nomor Invoice</th>
								<th>Customer</th>
								<th>Tanggal</th>
								<th>Nominal</th>
								<th>Jatuh Tempo</th>
								<th>Status</th>
								<th>Pilih Metode Pembayaran</th>
								<th>Approve</th>
							</tr>
						</thead>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

    <?php } ?>

    <div class="row">

        <div class="col-md-12">

            <div class="card card-info">

                <div class="card-header">

                    <h3 class="card-title">Data Approval</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                </div>
                <!-- /div.card-header -->

                <div class="card-body p-0">

                    <table id="table-approval-history" class="table table-striped table-valign-middle m-0" style="width: 100%">

                        <thead>
                            <tr>
                                <th>Nomor Invoice</th>
                                <th>Customer</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Status Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th></th>
                            </tr>
                        </thead>

                    </table>

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

			var tableApproval = $('#table-approval').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('acc/server_side_data_approval');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [1, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollX: '1000px',
                scrollCollapse: true,
                // Fix Column
                fixedColumns: {
                    leftColumns: 1
                },

                // Column
                columnDefs: [
                                { 
									targets: [ 1 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 5 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                },
                                {  
                                    targets: [ 6 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                },
                                {  
                                    targets: [ 7 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],

			});

			tableApproval.on('draw.dt', function () {
				currency_rp();
				single_datepicker();
			});

			var tableDP = $('#table-dp').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('acc/server_side_data_dp');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [2, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollX: '1000px',
                scrollCollapse: true,
                // Fix Column
                fixedColumns: {
                    leftColumns: 1
                },

                // Column
                columnDefs: [
                                { 
									targets: [ 2 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 8 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                },
                                {  
                                    targets: [ 9 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],

			});

		});

		var tablePiutang = $('#table-piutang').DataTable({

			// Data
            ajax: {
                url: "<?=site_url('acc/server_side_data_piutang');?>",
                type: "POST"
            },
            processing: true,
            paging: false,
            searching: false,
            info: false,
            order: [2, 'desc'],
            colReorder: true,
            scrollY: '400px',
            scrollX: '1000px',
            scrollCollapse: true,
            // Fix Column
            fixedColumns: {
                leftColumns: 1
            },

            // Column
            columnDefs: [
                            { 
                                targets: [ 2 ],
                                type: "de_datetime"
                            },
                            {  
                                targets: [ 6 ],
                                orderable: false,                                
                                className: "not-export-col"
                            },
                            {  
                                targets: [ 7 ],
                                orderable: false,                                
                                className: "not-export-col"
                            }
                        ],
            createdRow: function( row, data, dataIndex ) {

            	if (data[4] == '<div class="badge badge-danger">Hari ini</div>' 
                    || data[4] == '<div class="badge badge-danger">Besok</div>' 
                    || data[4] == '<div class="badge badge-danger">Lusa</div>') {
					$(row).addClass('due-date');
				}

			}

		});

		function approve_checked(param) {

			if ($('#approve_sales_' + param.value).is(':checked')) {
                    
                var payment_method_id = $('#payment_method_' + param.value).val();
                var sale_status = $('#sale_status_' + param.value).val();
                var nominal_bayar = $('#nominal_bayar_' + param.value).val();
                var due_date = $('#due_date_' + param.value).val();

                if (sale_status != 3 && (payment_method_id == 0 || payment_method_id == '' || payment_method_id == null)) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih Metode Pembayaran!',
                    });

                    $('#approve_sales_' + param.value).prop('checked', false);

                    return false;

                }

                if (sale_status == 3 && (due_date == 0 || due_date == '' || due_date == null)) {

                	Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih Jatuh Tempo!',
                    });

                    $('#approve_sales_' + param.value).prop('checked', false);

                    return false;

                }

                if (sale_status == 1 && (nominal_bayar == 'Rp 0.00' || nominal_bayar == 'Rp 0' || nominal_bayar == 'Rp ' || nominal_bayar == 'Rp')) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Isi nominal bayar!',
                    });

                    $('#approve_sales_' + param.value).prop('checked', false);

                    return false;

                }

            	Swal.fire({
                    title: 'Approve pembayaran?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Approve!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?=site_url('acc/submit_approval');?>",
                            type: "post",
                            data: {
                                id: param.value,
                                payment_method_id: payment_method_id,
                                sale_status: sale_status,
                                nominal_bayar: nominal_bayar,
                                due_date: due_date
                            },
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

                                        $('#table-approval').DataTable().ajax.reload();
                                        $('#table-dp').DataTable().ajax.reload();
                                        $('#table-piutang').DataTable().ajax.reload();
                                        $('#table-approval-history').DataTable().ajax.reload();

                                    }, 2000);

                                } else if (response == 'error') {
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Ada kesalahan!',
                                    });

                                }
                                
                            }
                        });

                    } else {
                        $('#approve_sales_' + param.value).prop('checked', false);
                    }

                });

            }

		}

		function approve_dp_checked(param) {

			if ($('#approve_dp_' + param.value).is(':checked')) {

				var dp_method_id = $('#dp_method_' + param.value).val();
				var nominal_bayar = $('#nominal_bayar_' + param.value).val();

				if (dp_method_id == 0 || dp_method_id == '' || dp_method_id == null) {

					Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih Metode Pembayaran!',
                    });

                    $('#approve_dp_' + param.value).prop('checked', false);

                    return false;

				}

				Swal.fire({
                    title: 'Approve pembayaran?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Approve!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                    	$.ajax({
                            url: "<?=site_url('acc/submit_dp');?>",
                            type: "post",
                            data: {
                                id: param.value,
                                dp_method_id: dp_method_id,
                                nominal_bayar: nominal_bayar
                            },
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

                                        $('#table-dp').DataTable().ajax.reload();
                                        $('#table-approval-history').DataTable().ajax.reload();

                                    }, 2000);

                                } else if (response == 'error') {
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Ada kesalahan!',
                                    });

                                }
                                
                            }
                        });
				
                    } else {
 						$('#approve_dp_' + param.value).prop('checked', false);
                    }

                });

			}

		}

		function approve_piutang_checked(param) {

			if ($('#approve_piutang_' + param.value).is(':checked')) {

				var payment_method_id = $('#payment_method_' + param.value).val();
				var nominal_bayar = $('#nominal_bayar_' + param.value).val();

				if (payment_method_id == 0 || payment_method_id == '' || payment_method_id == null) {

					Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih Metode Pembayaran!',
                    });

                    $('#approve_piutang_' + param.value).prop('checked', false);

                    return false;

				}

				Swal.fire({
                    title: 'Approve pembayaran?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Approve!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                    	$.ajax({
                            url: "<?=site_url('acc/submit_piutang');?>",
                            type: "post",
                            data: {
                                id: param.value,
                                payment_method_id: payment_method_id,
                                nominal_bayar: nominal_bayar
                            },
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

                                        $('#table-piutang').DataTable().ajax.reload();
                                        $('#table-approval-history').DataTable().ajax.reload();

                                    }, 2000);

                                } else if (response == 'error') {
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Ada kesalahan!',
                                    });

                                }
                                
                            }
                        });
				
                    } else {
 						$('#approve_piutang_' + param.value).prop('checked', false);
                    }

                });

			}

		}

	</script>

    
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
    </style>

    <script>

        var titleExport = 'Data Approval History';
        var columnExport = [ ':visible:not(.not-export-col)' ];

        $(document).ready( function () {

            $('#table-approval-history thead tr').clone(true).appendTo('#table-approval-history thead');

            $('#table-approval-history thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 2 :

                        $(this).html('<input type="text" class="form-control daterangepicker" style="position: static" placeholder="Cari ' + title + '" readonly />');

                        break;

                    case 4 :

                        $(this).html('<?=$filterStatus;?>');

                        break;

                    case 5 :

                        $(this).html('<?=$filterPaymentMethod;?>');

                        break;

                    case 6 :

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('.daterangepicker', this).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    
                    tableApprovalHistory
                        .column(i)
                        .search(this.value)
                        .draw();
                });

                $('.daterangepicker', this).on('cancel.daterangepicker', function(ev, picker) {
                
                    $(this).val("");

                    tableApprovalHistory
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('input', this).on('change', function () {

                    tableApprovalHistory
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

                    tableApprovalHistory
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

            var tableApprovalHistory = $('#table-approval-history').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('acc/server_side_data_history');?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,

                // Ordering
                order: [2, 'desc'],
                colReorder: true,
                orderCellsTop: true,

                // Header
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
                                    targets: [ 6 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],

                // // Buttons          
                // buttons: [ 
                //             'pageLength',
                //             'colvis', 
                //             {
                //                 extend: 'copy',
                //                 exportOptions: {
                //                     columns: columnExport
                //                 }
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

                // // Responsive
                // responsive: {

                //     details: {

                //         display: $.fn.dataTable.Responsive.display.modal( {

                //             header: function ( row ) {

                //                 var data = row.data();
                //                 return 'Details for ' + data[0];

                //             }

                //         }),

                //         renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                //             tableClass: 'table'
                //         })
                //     }
                // }

            });

        });

    </script>