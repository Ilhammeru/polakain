	
	<div class="row">

		<div class="col-md-12">

			<div class="card card-secondary">

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
                                    targets: [ 6 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],

                // Buttons          
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