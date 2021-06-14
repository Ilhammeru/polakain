

    <script type="text/javascript">

        var titleExport = 'Data Invoice Barang Datang';
        var columnExport = [ ':visible:not(.not-export-col)' ];
        var p_invoice_add = "<?php echo $this->session->userdata('p_invoice_add');?>";

        $(document).ready( function () {

            $(document).on('click', '#btn-confirm-delete', function () {
                var id = $(this).attr('key');

                 Swal.fire({
                    title: 'Hapus data?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({

                            url: "<?=site_url('invoice/delete_invoice');?>",
                            type: "post",
                            data: {
                                id: id
                            },
                            success: function(response) {

                                if (response == 'success') {

                                    $('#modal-confirm-delete').modal('hide');

                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Data berhasil dihapus',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    setTimeout(function(){
                                        $('#tableInvoice').DataTable().ajax.reload();
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
            });

            $('#tableInvoice thead tr').clone(true).appendTo('#tableInvoice thead');

            $('#tableInvoice thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 1 :

                        $(this).html('<?php echo $filterVendor;?>');

                        break;

                    case 2 :

                        $(this).html('<input type="text" class="form-control daterangepicker" style="position: static" placeholder="Cari ' + title + '" readonly />');

                        break;

                    case 4:

                        $(this).html('<?php echo $filterStatus;?>');

                        break;

                    case 5:

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('.daterangepicker', this).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    
                    tableInvoice
                        .column(i)
                        .search(this.value)
                        .draw();
                });

                $('.daterangepicker', this).on('cancel.daterangepicker', function(ev, picker) {
                
                    $(this).val("");

                    tableInvoice
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('input', this).on('change', function () {

                    tableInvoice
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

                    tableInvoice
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

            var tableInvoice  = $('#tableInvoice').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('invoice/server_side_data');?>",
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

                // Column
                columnDefs: [
                                {  
                                    targets: [ 5 ],
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
                //                     columns: columnExport,
                //                     orientation: 'landscape'
                //                 }
                //             },
                //             {
                //                 extend: 'print',
                //                 title: titleExport,
                //                 exportOptions: {
                //                     columns: columnExport,
                //                     orientation: 'landscape'
                //                 }
                //             },
                //             {
                //                 text: 'Input Barang Datang',
                //                 className: 'btn-success btn-invoice-add',
                //                 action: function () {
                //                     location.href = "<?=site_url('invoice/form_new_barang_datang');?>"       
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
        
            //tableInvoice.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

            if (p_invoice_add == 0) {
                $('.btn-invoice-add').hide();
            }

        });
        //

    </script>

    
    <?php
    if (date('H:i:s') > $closingDate) { ?>

        <script>
            $(document).ready( function () {
                $('.btn-invoice-add').hide();
            });
        </script>

    <?php } ?>


    <div class="row">
        
        <div class="col-md-12">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Data Invoice Barang Datang</h3>

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

                    <!--<div class="row">-->
                    <!--    <div class="col-12">-->
                    <!--        <a href="<?=site_url('invoice/form_new_barang_datang');?>" class="btn btn-success btn-sm">Input Barang Datang</a>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <table id="tableInvoice" class="table table-bordered table-valign-middle" style="width: 100%">

                        <thead>

                            <tr>
                                <th>Invoice</th>
                                <th>Vendor</th>
                                <th>Tanggal Invoice</th>
                                <th>Total</th>
                                <!-- <th>Diskon</th>
                                <th>PPN</th>
                                <th>Ongkir</th>
                                <th>Grand Total</th> -->
                                <th>Status</th>
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
