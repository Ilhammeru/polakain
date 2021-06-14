<!doctype html>

    <script type="text/javascript">

        var titleExport = 'Report Hak Akses';
        var columnExport = [ ':visible:not(.not-export-col)' ];

        $(document).ready( function () {

            $('#tableRole thead tr').clone(true).appendTo('#tableRole thead');

            $('#tableRole thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 1 :

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('input', this).on('change', function () {

                    tableRole
                        .column(i)
                        .search(this.value)
                        .draw();

                });

            });
            // End of clone thead

            var tableRole  = $('#tableRole').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('role/server_side_data');?>",
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
                                    targets: [ 1 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],
                columns: [
                            {
                                "width": "60%"
                            },
                            {
                                "width": "40%"
                            }
                        ],   

                // Buttons          
                buttons: [ 
                            'pageLength',
                            'colvis', 
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
                                text: 'Tambah Hak Akses',
                                className: 'btn-success',
                                action: function () {
                                    location.href = "<?=site_url('role/form_new_role');?>"       
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
                                return 'Details for ' + data[0];

                            }

                        }),

                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                }

            });
        
            tableRole.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

        });
        //

    </script>

    <div class="row">
        
        <div class="col-md-8 offset-md-2">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Data Hak Akses</h3>
                </div>
                
                <div class="card-body">    

                    <table id="tableRole" class="table table-bordered table-valign-middle">

                        <thead>

                            <tr>
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
