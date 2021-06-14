    
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

    <?php
    if (date('H:i:s') < $closingDate) { ?>

	<div class="row">
        
        <div class="col-md-12">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Hutang Dagang</h3>

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

                    <table id="table-payment" class="table table-striped table-bordered table-valign-middle m-0">

                        <thead>

                            <tr>
                                <th>Tanggal Invoice</th>
                                <th>Invoice</th>
                                <th>Vendor</th>
                                <th>Total</th>
                                <th>Diskon</th>
                                <th>PPN</th>
                                <th>Ongkir</th>
                                <th>Grand Total</th>
                                <th>Titipan</th>
                                <th>Pembayaran Dimuka</th>
                               	<th>Metode Pembayaran</th>
                                <th>Approve</th>
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

    <?php } ?>

    <div class="row">
        
        <div class="col-md-12">
            
            <div class="card card-info collapsed-card">
                
                <div class="card-header">
                    <h3 class="card-title">History</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">    

                    <table id="table-payment-history" class="table table-striped table-bordered table-valign-middle m-0">

                        <thead>

                            <tr>
                                <th>Tanggal Invoice</th>
                                <th>Invoice</th>
                                <th>Vendor</th>
                                <th>Nominal</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode Pembayaran</th>
                                <th>Aksi</th>
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

    <script>

    	$(document).ready( function () {

            var titleExport = 'Report Hutang Dagang';
            var columnExport = [ ':visible:not(.not-export-col)' ];

    		$('#table-payment').DataTable({

    			// Data
                ajax: {
                    url: "<?=site_url('payment/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [0, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollX: true,
                scrollCollapse: true,
                columnDefs: [
                                { 
									targets: [ 0 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 9 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                },
                                {  
                                    targets: [ 10 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                },
                                {  
                                    targets: [ 11 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ]

    		});

            $('#table-payment-history thead tr').clone(true).appendTo('#table-payment-history thead');

            $('#table-payment-history thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 0 :

                        $(this).html('<input type="text" class="form-control daterangepicker" style="position: static" placeholder="Cari ' + title + '" readonly />');

                        break;

                    case 2 :

                        $(this).html('<?=$filterVendor;?>');

                        break;

                    case 4 :

                        $(this).html('<input type="text" class="form-control daterangepicker" style="position: static" placeholder="Cari ' + title + '" readonly />');

                        break;

                    case 5 :

                        $(this).html('');

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
                    
                    tablePaymentHistory
                        .column(i)
                        .search(this.value)
                        .draw();
                });

                $('.daterangepicker', this).on('cancel.daterangepicker', function(ev, picker) {
                
                    $(this).val("");

                    tablePaymentHistory
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('input', this).on('change', function () {

                    tablePaymentHistory
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

                    tablePaymentHistory
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

            var tablePaymentHistory = $('#table-payment-history').DataTable({

                ajax: {
                    url: "<?=site_url('payment/server_side_data_history');?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,

                // Ordering
                order: [4, 'desc'],
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

                // Column
                columnDefs: [
                                {  
                                    targets: [ 5 ],
                                    orderable: false                             
                                },
                                {  
                                    targets: [ 6 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],  

                columns: [
                            {
                                "width": "10%"
                            },
                            {
                                "width": "10%"
                            },
                            {
                                "width": "20%"
                            },
                            {
                                "width": "10%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "20%"
                            },
                            {
                                "width": "15%"
                            },
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
                //                 return 'Details for ' + data[1];

                //             }

                //         }),

                //         renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                //             tableClass: 'table'
                //         })
                //     }
                // }

            });
        
            //tablePaymentHistory.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

    	});	

        function approve_checked(param) {

            if ($('#approve_payment_' + param.value).is(':checked')) {

                var payment_dp_id = 0;
                    
                $('.payment_dp_' + param.value).each( function () {

                    var checkbox = $(this).data('id');

                    if ($('#payment_dp_' + checkbox).is(':checked')) {
                        payment_dp_id += '-';
                        payment_dp_id += $('#payment_dp_' + checkbox).data('id');
                    }

                });

                var payment_method_id = $('#payment_method_' + param.value).val();

                if (payment_method_id == 0 || payment_method_id == '' || payment_method_id == null) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih Metode Pembayaran!',
                    });

                    $('#approve_payment_' + param.value).prop('checked', false);

                    return false;

                }

                Swal.fire({
                    title: 'Approve invoice?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Approve!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?=site_url('payment/approve');?>",
                            type: "post",
                            data: {
                                id: param.value,
                                payment_dp_id: payment_dp_id,
                                payment_method_id: payment_method_id
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

                                        $('#table-payment').DataTable().ajax.reload();
                                        $('#table-payment-history').DataTable().ajax.reload();

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
                        $('#approve_payment_' + param.value).prop('checked', false);
                    }

                });

            }

        }

        function cancel_approve(param) {

            Swal.fire({
                title: 'Cancel approve?',
                //text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya!'
                }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?=site_url('payment/cancel_approve');?>",
                        type: "post",
                        data: {
                            invoice_id: param
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

                                    $('#table-payment').DataTable().ajax.reload();
                                    $('#table-payment-history').DataTable().ajax.reload();

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

                }

            });

        }

        // function update_nominal(param) {

        //     var value = $('#payment_dp_' + param + ' option:selected').data('nominal');
        //     var method = $('#payment_dp_' + param + ' option:selected').data('method');
        //     var grand_total = $('#input_grand_total_' + param).val();

            // var final = grand_total - value;

            // if (final == 0) {

            //     $('#payment_method_' + param).val(method);
            //     $('#payment_method_' + param).hide();

            // }

            // console.log(method);

            // final = number_format(final);

            // $('#grand_total_' + param).html(final);

        // }

        function update_nominal(param) {

            var total_dp = 0;
            var method = null;

            $('.payment_dp_' + param).each( function () {

                var checkbox = $(this).data('id');

                if ($('#payment_dp_' + checkbox).is(':checked')) {
                    var nominal = $('#payment_dp_' + checkbox).data('nominal');
                    
                    method = $('#payment_dp_' + checkbox).data('method');
                    
                    total_dp += +nominal;
                }

            });

            var grand_total = $('#input_grand_total_' + param).val();
            var final = 0;

            final = grand_total - total_dp;

            if (final <= 0) {
                $('#payment_method_' + param).val(method);
                $('#payment_method_' + param).hide();
            } else {
                $('#payment_method_' + param).val('');
                $('#payment_method_' + param).show();
            }

            final = number_format(final);

            $('#grand_total_' + param).html(final);

        }

    </script>
