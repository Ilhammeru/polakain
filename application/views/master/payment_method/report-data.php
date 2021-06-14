<!doctype html>

    <script type="text/javascript">

        var titleExport = 'Report Metode Pembayaran';
        var columnExport = [ ':visible:not(.not-export-col)' ];
        var p_payment_method_add = "<?php echo $this->session->userdata('p_payment_method_add');?>";

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

                            url: "<?=site_url('payment_method/delete_payment_method');?>",
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
                                        $('#tablePaymentMethod').DataTable().ajax.reload();
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
                        // End of ajax

                    }

                });

            });

            $('#tablePaymentMethod thead tr').clone(true).appendTo('#tablePaymentMethod thead');

            $('#tablePaymentMethod thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 5 :

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('input', this).on('change', function () {

                    tablePaymentMethod
                        .column(i)
                        .search(this.value)
                        .draw();

                });

            });
            // End of clone thead

            var tablePaymentMethod = $('#tablePaymentMethod').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('payment_method/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,

                // Ordering
                order: [0, 'asc'],
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
                columns: [
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
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
                //             },
                //             {
                //                 text: 'Tambah Metode Pembayaran',
                //                 className: 'btn-success btn-payment-method-add',
                //                 action: function () {
                //                     location.href = "<?=site_url('payment_method/form_new_payment_method');?>"       
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
        
            //tablePaymentMethod.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

            if (p_payment_method_add == 0) {
                $('.btn-payment-method-add').hide();
            }

        });
        //

    </script>

    <div class="row">
        
        <div class="col-md-10 offset-md-1">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Data Metode Pembayaran</h3>
                </div>
                
                <div class="card-body">    

                    <div class="row">
                        <div class="col-12">
                            <a href="<?=site_url('payment_method/form_new_payment_method');?>" class="btn btn-success btn-sm">Tambah Metode Pembayaran</a>
                        </div>
                    </div>

                    <table id="tablePaymentMethod" class="table table-bordered table-valign-middle">

                        <thead>

                            <tr>
                                <th>Tipe</th>
                                <th>Nama</th>
                                <th>Bank</th>
                                <th>Nomor Rekening</th>
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
