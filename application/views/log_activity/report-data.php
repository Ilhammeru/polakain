<!doctype html>

    <script type="text/javascript">

        var titleExport = 'Report Aktifitas User';
        var columnExport = [ ':visible:not(.not-export-col)' ];

        $(document).ready( function () {

            $('#tableLogActivity thead tr').clone(true).appendTo('#tableLogActivity thead');

            $('#tableLogActivity thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 0 :

                        $(this).html('<input type="text" class="form-control daterangepicker" style="position: static" placeholder="Cari ' + title + '" readonly />');

                        break;

                    case 2 :

                        $(this).html('<?=$filterUsers;?>');

                        break;

                    case 3 :

                        $(this).html('<?=$filterAccess;?>');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('.daterangepicker', this).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    
                    tableLogActivity
                        .column(i)
                        .search(this.value)
                        .draw();
                });

                $('.daterangepicker', this).on('cancel.daterangepicker', function(ev, picker) {
                
                    $(this).val("");

                    tableLogActivity
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('input', this).on('change', function () {

                    tableLogActivity
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

                    tableLogActivity
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

            var tableLogActivity  = $('#tableLogActivity').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('log_activity/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,

                // Ordering
                order: [0, 'desc'],
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
                                    targets: [ 2 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],
                columns: [
                            {
                                "width": "25%"
                            },
                            {
                                "width": "25%"
                            },
                            {
                                "width": "25%"
                            },
                            {
                                "width": "25%"
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
                            }
                        ],
                dom: 'Bfrtip',

                // Responsive
                responsive: {

                    details: {

                        display: $.fn.dataTable.Responsive.display.modal( {

                            header: function ( row ) {

                                var data = row.data();
                                return 'Details for ' + data[1] + ' [' + data[2] + ']';

                            }

                        }),

                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                }

            });
        
            tableLogActivity.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

        });
        //

    </script>

    <div class="row">
        
        <div class="col-12">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Data Aktifitas User</h3>
                </div>
                
                <div class="card-body">    

                    <table id="tableLogActivity" class="table table-bordered table-valign-middle">

                        <thead>

                            <tr>
                                <th><i class="fa fa-calendar"></i> Tanggal</th>
                                <th><i class="fa fa-list-alt"></i> Aktifitas</th>
                                <th><i class="fa fa-user"></i> User</th>
                                <th><i class="fa fa-list-alt"></i> Menu</th>
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
