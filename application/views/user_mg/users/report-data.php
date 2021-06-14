<!doctype html>

    <script type="text/javascript">

        var titleExport = 'Report Users';
        var columnExport = [ ':visible:not(.not-export-col)' ];

        $(document).ready( function () {

            $(document).on('click', '#btn-confirm-delete', function () {
                var id = $(this).attr('key');

                 Swal.fire({
                    title: 'Hapus user?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({

                            url: "<?=site_url('users/delete_user');?>",
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
                                        title: 'Data berhasil disimpan',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    setTimeout(function(){
                                        $('#tableUsers').DataTable().ajax.reload();
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

            $('#tableUsers thead tr').clone(true).appendTo('#tableUsers thead');

            $('#tableUsers thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 2 :
                        
                        $(this).html('<?php echo $filterRole;?>');

                        break;

                    case 3 :

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('input', this).on('change', function () {

                    tableUsers
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

                    tableUsers
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

            var tableUsers  = $('#tableUsers').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('users/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,

                // Ordering
                order: [0, 'asc'],
                colReorder: true,
                orderCellsTop: true,

                // Header
                fixedHeader: {
                    headerOffset: 55
                },

                // Select
                select: {
                    style: 'multi'
                },

                // Length
                lengthChange: false,
                lengthMenu: [
                    [ 10, 25, 50, 100],
                    [ '10 rows', '25 rows', '50 rows', '100 rows']
                ],

                // Column
                columnDefs: [
                                {  
                                    targets: [ 3 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],
                columns: [
                            {
                                "width": "30%"
                            },
                            {
                                "width": "25%"
                            },
                            {
                                "width": "25%"
                            },
                            {
                                "width": "20%"
                            }
                        ],   

                // Buttons          
                buttons: [ 
                            'pageLength',
                            {
                                extend: 'copy',
                                exportOptions: {
                                    columns: columnExport
                                }
                            },
                            {
                                extend: 'excel',
                                title: titleExport,
                                exportOptions: {
                                    columns: columnExport
                                }
                            },
                            {
                                extend: 'pdf',
                                title: titleExport,
                                exportOptions: {
                                    columns: columnExport
                                }
                            },
                            {
                                extend: 'print',
                                title: titleExport,
                                exportOptions: {
                                    columns: columnExport
                                }
                            },
                            {
                                text: 'Tambah User',
                                className: 'btn-success',
                                action: function () {
                                    location.href = "<?=site_url('users/form_new_user');?>"       
                                }
                            } 
                        ],
                dom: 'Bfrtip',

                // Responsive
                responsive: {

                    details: {

                        display: $.fn.dataTable.Responsive.display.modal( {

                            header: function ( row ) {

                                var data = row.data();
                                return 'Details for ' + data[0] + ' [' + data[2] + ']';

                            }

                        }),

                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                }

            });
        
            tableUsers.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

        });
        //

    </script>

    <div class="row">
        
        <div class="col-md-8 offset-md-2">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Data Users</h3>
                </div>
                
                <div class="card-body">    

                    <table id="tableUsers" class="table table-bordered table-valign-middle">

                        <thead>

                            <tr>
                                <th><i class="fa fa-user"></i> Username</th>
                                <th> Dept</th>
                                <th><i class="fa fa-key"></i> Hak Akses</th>
                                <th><i class="fa fa-wrench"></i> Aksi</th>
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
